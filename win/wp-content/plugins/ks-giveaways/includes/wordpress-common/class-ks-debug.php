<?php

if (!class_exists('KS_Debug')) {

class KS_Debug
{
    public static function check_table_exists($table_name)
    {
        global $wpdb;

        $table_name = $wpdb->prefix . $table_name;
        $query = "SELECT * FROM {$table_name} LIMIT 1";
        $ret = $wpdb->query($query);

        if ($ret === false) {
            throw new Exception($wpdb->last_error);
        }
    }

    public static function is_wp_engine()
    {
        return (defined('WPE_ISP') && WPE_ISP);
    }

    public static function php_version($minimum_version)
    {
        return version_compare(PHP_VERSION, $minimum_version, '>=');
    }
}

}
