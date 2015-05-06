<?php

if (!class_exists('KS_Database_Table')) {

if (version_compare(PHP_VERSION, '5.3', '>=')) {
    require_once 'class-ks-database-53.php';
} else {

if (!function_exists('php52_get_called_class')) {

    class ks_class_tools {
        static function get_called_class() {
            $bt = debug_backtrace();

            $lines = file($bt[2]['file']);

            preg_match_all('/([a-zA-Z0-9\_]+)::'.$bt[2]['function'].'/', $lines[$bt[2]['line']-1], $matches);

            if (isset($matches[1][0]) && $matches[1][0] !== 'self') {
                return $matches[1][0];
            }

            for ($i = 3; $i < count($bt); $i++) {
                if ($bt[$i]['type'] === '::' && $bt[$i]['class'] !== 'self') {
                    return $bt[$i]['class'];
                }
            }

            return false;
        }
    }

    function php52_get_called_class() {
        return ks_class_tools::get_called_class();
    }
}

interface KS_Database_Table_Interface
{
    public static function install_table();
}

abstract class KS_Database_Table implements KS_Database_Table_Interface
{
    protected static $table_name = null;
    protected static $table_names = array();

    public static function check_database_table($version)
    {
        $cls = php52_get_called_class();
        $key = strtolower($cls);

        if (get_option($key) != $version) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';

            call_user_func(array($cls, 'install_table'));

            update_option($key, $version);
        }
    }

    public static function get_tablename()
    {
        global $wpdb;

        $cls = php52_get_called_class();

        if (!isset(self::$table_names[$cls])) {
            $vars = get_class_vars($cls);
            $tablename = isset($vars['table_name']) ? $vars['table_name'] : null;

            if (!$tablename) {
                die('Please override self::$table_name');
            }

            self::$table_names[$cls] = $tablename;
        }

        return $wpdb->prefix . self::$table_names[$cls];
    }
}

}
}