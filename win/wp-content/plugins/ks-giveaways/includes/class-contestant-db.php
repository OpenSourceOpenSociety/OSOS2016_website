<?php

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'wordpress-common' . DIRECTORY_SEPARATOR . 'class-ks-database.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-entry-db.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-helper.php';

class KS_Contestant_DB extends KS_Database_Table
{
    protected static $table_name = 'ks_giveaways_contestant';

    public static function install_table()
    {
        $table = self::get_tablename();

        $sql = "CREATE TABLE {$table} (
  ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  contest_id bigint(20) unsigned NOT NULL,
  email_address varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  date_added TIMESTAMP NULL,
  confirm_key varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  status ENUM('unconfirmed','confirmed') DEFAULT 'unconfirmed',
  PRIMARY KEY  (ID),
  KEY contestant_contest_id (contest_id),
  KEY contestant_contest_email_address (contest_id,email_address)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

        dbDelta($sql);
    }

    public static function get_results($contest_id, $offset = null, $per_page = null, $orderby = 'date_added', $order = 'desc')
    {
        global $wpdb;

        $table = self::get_tablename();

        $entry_table = KS_Entry_DB::get_tablename();

        $count_query = "SELECT COUNT(*) FROM {$entry_table} WHERE `contestant_id` = {$table}.`ID`";

        $query = $wpdb->prepare("SELECT *,($count_query) AS num_entries FROM {$table} WHERE `contest_id` = %d ORDER BY `" . $orderby . "` " . strtoupper($order), $contest_id);

        if ($offset !== null) {
            $query .= $wpdb->prepare(" LIMIT %d", $offset);
        }

        if ($per_page !== null) {
            $query .= $wpdb->prepare(",%d", $per_page);
        }

        return $wpdb->get_results($query, ARRAY_A);
    }

    public static function get_total($contest_id)
    {
        global $wpdb;

        $table = self::get_tablename();

        $query = $wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE `contest_id` = %d", $contest_id);

        return (int) $wpdb->get_var($query);
    }

    public static function get_existing($contest_id, $email_address)
    {
        global $wpdb;

        $table = self::get_tablename();

        $query = $wpdb->prepare("SELECT * FROM {$table} WHERE `contest_id` = %d AND `email_address` = %s", $contest_id, $email_address);

        return $wpdb->get_row($query);
    }

    public static function remove($contestant_id)
    {
        global $wpdb;

        KS_Entry_DB::remove_contestant($contestant_id);

        $table = self::get_tablename();

        $data = array('ID' => $contestant_id);
        $format = array('%d');
        $wpdb->delete($table, $data, $format);
    }

    public static function get($contestant_id, $contest_id = null)
    {
        global $wpdb;

        $table = self::get_tablename();

        if (null == $contest_id) {
            $query = $wpdb->prepare("SELECT * FROM {$table} WHERE `ID` = %d", $contestant_id);
        } else {
            $query = $wpdb->prepare("SELECT * FROM {$table} WHERE `ID` = %d AND `contest_id` = %d", $contestant_id, $contest_id);
        }

        return $wpdb->get_row($query);
    }

    public static function add($contest_id, $email_address)
    {
        global $wpdb;

        $table = self::get_tablename();
        $data = array(
            'contest_id' => $contest_id,
            'email_address' => $email_address,
            'confirm_key' => md5(uniqid()),
            'date_added' => current_time('mysql', true)
        );
        $format = array('%d', '%s', '%s', '%s');

        if ($wpdb->insert($table, $data, $format)) {
            do_action('ks_giveaways_add_contestant', $email_address);

            return self::get_existing($contest_id, $email_address);
        }

        return false;
    }

    public static function update_status($contestant_id, $status = 'confirmed')
    {
        global $wpdb;

        $table = self::get_tablename();

        $data = array('status' => $status);
        $where = array('ID' => $contestant_id);
        $format = array('%s');
        $where_format = array('%d');
        $wpdb->update($table, $data, $where, $format, $where_format);
    }

    public static function get_referees($contestant_id)
    {
        /** @var wpdb $wpdb  */
        global $wpdb;

        $contestant_tname = self::get_tablename();
        $entry_tname = KS_Entry_DB::get_tablename();

        // get refereee from first entry
        $query = $wpdb->prepare("SELECT {$contestant_tname}.`email_address`
                                 FROM {$entry_tname} LEFT JOIN {$contestant_tname}
                                 ON {$contestant_tname}.ID = {$entry_tname}.`referral_id`
                                 WHERE {$entry_tname}.`contestant_id` = %d
                                 ORDER BY {$entry_tname}.`date_added`,{$entry_tname}.`ID` ASC LIMIT 1", $contestant_id);

        $result = $wpdb->get_results($query);

        $referral_emails = array();

        foreach($result as $v)
        {
            if ($v->email_address) {
                $referral_emails[] = $v->email_address;
            }
        }

        return $referral_emails;
    }

    public static function output_csv($contest_id)
    {
        $use_mysqli = function_exists('mysqli_init') ? true : false;

        if ($use_mysqli) {
            $port = null;
            $socket = null;
            $host = DB_HOST;
            $port_or_socket = strstr($host, ':');
            if (!empty($port_or_socket)) {
                $host = substr($host, 0, strpos($host, ':'));
                $port_or_socket = substr($port_or_socket, 1);
                if (0 !== strpos($port_or_socket, '/')) {
                    $port = intval($port_or_socket);
					          $maybe_socket = strstr($port_or_socket, ':');
					          if (!empty($maybe_socket)) {
						            $socket = substr($maybe_socket, 1);
                    }
                } else {
                    $socket = $port_or_socket;
                }
            }

            $dbh = mysqli_init();
            if (!$dbh->real_connect($host, DB_USER, DB_PASSWORD, DB_NAME, $port, $socket)) {
                wp_die('Error connecting to MySQL server: ' . $dbh->connect_error);
            }

        } else {
            $dbh = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD, true);
            if (!$dbh) {
                wp_die('Error connecting to MySQL server: ' . mysql_error());
            }

            if (!mysql_select_db(DB_NAME, $dbh)) {
                $error = mysql_error($dbh);
                mysql_close($dbh);
                wp_die('Error selecting MySQL database: ' . $error);
            }
        }

        global $wpdb;

        $table = self::get_tablename();
        $query = $wpdb->prepare("SELECT * FROM {$table} WHERE `contest_id` = %d ORDER BY `date_added` ASC", $contest_id);

        if ($use_mysqli) {
            $result = $dbh->query($query, MYSQLI_USE_RESULT);
            if (!$result) {
                $error = $dbh->error;
                $dbh->close();
                wp_die('Error executing query: ' . $error);
            }
        } else {
            $result = mysql_query($query, $dbh);
            if (!$result) {
                $error = mysql_error($dbh);
                mysql_close($dbh);
                wp_die('Error executing query: ' . $error);
            }
        }

        $row = array(
            'Email Address',
            'Date Added',
            'Status',
            'ID',
            'Lucky URL',
            'Confirm URL',
            'Entries',
            'Referrer'
        );

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="contestants.csv"');
        echo implode(',', $row);
        echo "\r\n";

        while ($contestant = ($use_mysqli ? $result->fetch_object() : mysql_fetch_object($result))) {
            $row = array(
                $contestant->email_address,
                get_date_from_gmt($contestant->date_added),
                $contestant->status,
                $contestant->ID,
                KS_Helper::get_lucky_url($contest_id, $contestant),
                KS_Helper::get_confirm_url($contest_id, $contestant),
                KS_Entry_DB::get_total($contestant->ID),
                implode(";", self::get_referees($contestant->ID))
            );

            echo implode(',', $row);
            echo "\r\n";
        }

        if ($use_mysqli) {
            $result->close();
            $dbh->close();
        } else {
            mysql_free_result($result);
            mysql_close($dbh);
        }

        exit;
    }
}