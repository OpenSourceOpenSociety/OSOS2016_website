<?php

if (!class_exists('KS_Database_Table')) {

interface KS_Database_Table_Interface
{
    public static function install_table();
}

abstract class KS_Database_Table implements KS_Database_Table_Interface
{
    protected static $table_name = null;

    public static function check_database_table($version)
    {
        $cls = function_exists('get_called_class') ? get_called_class() : php52_get_called_class();
        $key = strtolower($cls);

        if (get_option($key) != $version) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';

            static::install_table();

            update_option($key, $version);
        }
    }

    public static function get_tablename()
    {
        if (!static::$table_name) {
            die('Please override self::$table_name');
        }

        global $wpdb;

        return $wpdb->prefix . static::$table_name;
    }
}

}