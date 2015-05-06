<?php

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'wordpress-common' . DIRECTORY_SEPARATOR . 'class-ks-database.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-entry-db.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-helper.php';

class KS_Winner_DB extends KS_Database_Table
{
    protected static $table_name = 'ks_giveaways_winner';

    public static function install_table()
    {
        $table = self::get_tablename();

        $sql = "CREATE TABLE {$table} (
  ID bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  contest_id bigint(20) unsigned NOT NULL,
  contestant_id bigint(20) unsigned NOT NULL,
  email_address varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  date_drawn TIMESTAMP NULL,
  date_notified TIMESTAMP NULL,
  winner_name varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  winner_avatar varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  status ENUM('unconfirmed','confirmed','notified') DEFAULT 'unconfirmed',
  PRIMARY KEY  (ID),
  KEY winner_contest_id (contest_id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";

        dbDelta($sql);
    }

    public static function get_results($contest_id, $offset = null, $per_page = null)
    {
        global $wpdb;

        $table = self::get_tablename();

        $entry_table = KS_Entry_DB::get_tablename();
        $query = $wpdb->prepare("SELECT * FROM {$table} WHERE `contest_id` = %d", $contest_id);

        if ($offset !== null) {
            $query .= $wpdb->prepare(" LIMIT %d", $offset);
        }

        if ($per_page !== null) {
            $query .= $wpdb->prepare(",%d", $per_page);
        }

        return $wpdb->get_results($query, ARRAY_A);
    }

    public static function remove($winner_id)
    {
        global $wpdb;

        $table = self::get_tablename();

        $data = array('ID' => $winner_id);
        $format = array('%d');
        $wpdb->delete($table, $data, $format);
    }

    public static function get_total($contest_id)
    {
        global $wpdb;

        $table = self::get_tablename();

        $query = $wpdb->prepare("SELECT COUNT(*) FROM {$table} WHERE `contest_id` = %d", $contest_id);

        return (int) $wpdb->get_var($query);
    }

    public static function replace_winner($contest_id, $winner_id, $contestant)
    {
        global $wpdb;

        $table = self::get_tablename();

        $data = array(
            'ID' => $winner_id,
            'contest_id' => $contest_id,
            'contestant_id' => $contestant->ID,
            'email_address' => $contestant->email_address,
            'winner_name' => '',
            'winner_avatar' => '',
            'date_drawn' => gmdate('c'),
            'status' => 'unconfirmed'
        );
        $format = array('%d','%d','%d','%s','%s','%s','%s','%s');
        $wpdb->replace($table, $data, $format);
    }

    public static function insert_winner($contest_id, $contestant)
    {
        global $wpdb;

        $table = self::get_tablename();

        $wpdb->insert($table, array(
            'contest_id' => $contest_id,
            'contestant_id' => $contestant->ID,
            'email_address' => $contestant->email_address,
            'date_drawn' => current_time('mysql', true)
        ), array('%d', '%d', '%s', '%s'));
    }

    public static function update_status($winner_id, $status = 'confirmed')
    {
        global $wpdb;

        $table = self::get_tablename();

        $data = array('status' => $status);
        $where = array('ID' => $winner_id);
        $format = array('%s');
        $where_format = array('%d');
        $wpdb->update($table, $data, $where, $format, $where_format);
    }

    public static function update_name($winner_id, $name = '')
    {
        global $wpdb;

        $table = self::get_tablename();

        $data = array('winner_name' => $name);
        $where = array('ID' => $winner_id);
        $format = array('%s');
        $where_format = array('%d');
        $wpdb->update($table, $data, $where, $format, $where_format);
    }


    public static function update_avatar($winner_id, $url = '')
    {
        global $wpdb;

        $table = self::get_tablename();

        $data = array('winner_avatar' => $url);
        $where = array('ID' => $winner_id);
        $format = array('%s');
        $where_format = array('%d');
        $wpdb->update($table, $data, $where, $format, $where_format);
    }
    public static function get($winner_id, $contest_id = null)
    {
        global $wpdb;

        $table = self::get_tablename();

        if (null == $contest_id) {
            $query = $wpdb->prepare("SELECT * FROM {$table} WHERE `ID` = %d", $winner_id);
        } else {
            $query = $wpdb->prepare("SELECT * FROM {$table} WHERE `ID` = %d AND `contest_id` = %d", $winner_id, $contest_id);
        }

        return $wpdb->get_row($query);
    }

    public static function notify($winner_id)
    {
        $winner = self::get($winner_id);

        KS_Helper::send_winner_email($winner);

        global $wpdb;

        $table = self::get_tablename();

        $data = array('date_notified' => current_time('mysql', true));
        $where = array('ID' => $winner_id);
        $format = array('%s');
        $where_format = array('%d');
        $wpdb->update($table, $data, $where, $format, $where_format);

        self::update_status($winner_id, 'notified');
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
        $query = $wpdb->prepare("SELECT * FROM {$table} WHERE `contest_id` = %d ORDER BY `date_drawn` ASC", $contest_id);

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
            'Winner Name',
            'Date Drawn',
            'Date Notified',
            'Status'
        );

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="winners.csv"');
        echo implode(',', $row);
        echo "\r\n";

        while ($winner = ($use_mysqli ? $result->fetch_object() : mysql_fetch_object($result))) {
            $row = array(
                $winner->email_address,
                $winner->winner_name,
                get_date_from_gmt($winner->date_drawn),
                $winner->date_notified ? get_date_from_gmt($winner->date_notified) : '',
                $winner->status
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