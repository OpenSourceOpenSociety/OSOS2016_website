<?php

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'wordpress-common' . DIRECTORY_SEPARATOR . 'class-ks-database.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-contestant-db.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-winner-db.php';

class KS_Entry_DB extends KS_Database_Table
{
    protected static $table_name = 'ks_giveaways_entry';

    public static function install_table()
    {
        $table = self::get_tablename();

        $sql = "CREATE TABLE {$table} (
  ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  contestant_id bigint(20) unsigned NOT NULL,
  referral_id bigint(20) unsigned,
  ip_address varchar(20) CHARACTER SET utf8 NOT NULL,
  date_added TIMESTAMP NULL,
  PRIMARY KEY  (ID),
  KEY entry_contestant (contestant_id),
  KEY entry_referral (referral_id),
  KEY entry_contestant_referral (contestant_id,referral_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

        dbDelta($sql);
    }

    public static function get_results($contestant_id)
    {
        global $wpdb;

        $table = self::get_tablename();

        $query = $wpdb->prepare("SELECT * FROM {$table} WHERE `contestant_id` = %d", $contestant_id);

        return $wpdb->get_results($query, ARRAY_A);
    }

    public static function get_total($contestant_id)
    {
        global $wpdb;

        $table = self::get_tablename();

        $query = $wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE `contestant_id` = %d", $contestant_id);

        return (int) $wpdb->get_var($query);
    }

    public static function remove_contestant($contestant_id)
    {
        global $wpdb;

        $table = self::get_tablename();

        $data = array('contestant_id' => $contestant_id);
        $format = array('%d');
        $wpdb->delete($table, $data, $format);

        // remove referrer on entries
        $data = array('referral_id' => 'NULL');
        $where = array('referral_id' => $contestant_id);
        $where_format = array('%d');

        add_filter('query', array(__CLASS__, 'wpse_143405_query'));
        $wpdb->update($table, $data, $where, null, $where_format);
        remove_filter('query', array(__CLASS__, 'wpse_143405_query'));
    }

    public static function wpse_143405_query($query)
    {
        return str_ireplace("'NULL'", "NULL", $query);
    }

    public static function get_contest_total($contest_id)
    {
        global $wpdb;

        $table = self::get_tablename();
        $contestant_table = KS_Contestant_DB::get_tablename();

        $query = $wpdb->prepare("SELECT COUNT(*) FROM {$table} JOIN {$contestant_table} ON {$contestant_table}.`ID` = {$table}.`contestant_id` WHERE {$contestant_table}.`contest_id` = %d", $contest_id);

        return (int) $wpdb->get_var($query);
    }

    public static function draw($contest_id, $overwrite_id = null, $exclude = null)
    {
        global $wpdb;

        $table = self::get_tablename();
        $contestant_table = KS_Contestant_DB::get_tablename();
        $winner_table = KS_Winner_DB::get_tablename();

        $query = trim($wpdb->prepare("
          SELECT COUNT({$table}.`ID`) FROM {$table} JOIN {$contestant_table} ON {$contestant_table}.`ID` = {$table}.`contestant_id` WHERE {$contestant_table}.`contest_id` = %d
          AND {$contestant_table}.`ID` NOT IN (SELECT `contestant_id` FROM {$winner_table} WHERE contest_id = %d)
        ", $contest_id, $contest_id));

        $count = (int) $wpdb->get_var($query);
        $offset = rand(0, $count - 1);

        $query = trim($wpdb->prepare("
          SELECT {$contestant_table}.* FROM {$table} JOIN {$contestant_table} ON {$contestant_table}.`ID` = {$table}.`contestant_id` WHERE {$contestant_table}.`contest_id` = %d
          AND {$contestant_table}.`ID` NOT IN (SELECT `contestant_id` FROM {$winner_table} WHERE contest_id = %d)
          LIMIT {$offset},1
        ", $contest_id, $contest_id));

        $contestant = $wpdb->get_row($query);

        if ($contestant) {
            if ($overwrite_id) {
                KS_Winner_DB::replace_winner($contest_id, $overwrite_id, $contestant);
            } else {
                KS_Winner_DB::insert_winner($contest_id, $contestant);
            }
        }
    }

    public static function add($contestant_id, $referral_id)
    {
        global $wpdb;

        $table = self::get_tablename();
        $data = array(
            'contestant_id' => $contestant_id,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'date_added' => current_time('mysql', true)
        );
        $format = array('%d','%s','%s');

        if ($referral_id) {
            $data['referral_id'] = $referral_id;
            $format[] = '%d';
        }

        if ($wpdb->insert($table, $data, $format)) {
            return true;
        }

        return false;
    }

    public static function has_referral($contestant_id, $referral_id)
    {
        global $wpdb;

        $table = self::get_tablename();
        $query = $wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE `contestant_id` = %d AND `referral_id` = %d", $contestant_id, $referral_id);

        return ( (int) $wpdb->get_var($query) > 0);
    }
}