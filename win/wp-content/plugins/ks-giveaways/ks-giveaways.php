<?php
/**
 * WordPress Plugin Boilerplate.
 *
 * @wordpress-plugin
 * Plugin Name:       KingSumo Giveaways
 * Plugin URI:        http://kingsumo.com/apps/giveaways
 * Description:       Viral Giveaways for WordPress.
 * Version:           1.2.2
 * Author:            KingSumo
 * Author URI:        http://kingsumo.com
 * Text Domain:       plugin-name-locale
 * Domain Path:       /languages
 */

if (!defined('WPINC')) {
    die;
}

// ensure it matches EDD backend
define('KS_GIVEAWAYS_EDD_NAME', 'KingSumo Giveaways');
define('KS_GIVEAWAYS_EDD_VERSION', '1.2.2');
define('KS_GIVEAWAYS_EDD_URL', 'http://kingsumo.com');
define('KS_GIVEAWAYS_EDD_AUTHOR', 'KingSumo');

define('KS_GIVEAWAYS_POST_TYPE', 'ks_giveaway');

define('KS_GIVEAWAYS_PLUGIN_FILE', __FILE__);
define('KS_GIVEAWAYS_PLUGIN_DIR', dirname(__FILE__));
define('KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR', KS_GIVEAWAYS_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'includes');

define('KS_GIVEAWAYS_PLUGIN_PUBLIC_DIR', KS_GIVEAWAYS_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'public');
define('KS_GIVEAWAYS_PLUGIN_PUBLIC_INCLUDES_DIR', KS_GIVEAWAYS_PLUGIN_PUBLIC_DIR . DIRECTORY_SEPARATOR . 'includes');
define('KS_GIVEAWAYS_PLUGIN_PUBLIC_VIEWS_DIR', KS_GIVEAWAYS_PLUGIN_PUBLIC_DIR . DIRECTORY_SEPARATOR . 'views');

define('KS_GIVEAWAYS_PLUGIN_TEMPLATES_DIR', KS_GIVEAWAYS_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'templates');

define('KS_GIVEAWAYS_PLUGIN_ADMIN_DIR', KS_GIVEAWAYS_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'admin');
define('KS_GIVEAWAYS_PLUGIN_ADMIN_INCLUDES_DIR', KS_GIVEAWAYS_PLUGIN_ADMIN_DIR . DIRECTORY_SEPARATOR . 'includes');
define('KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR', KS_GIVEAWAYS_PLUGIN_ADMIN_DIR . DIRECTORY_SEPARATOR . 'views');

define('KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY', 'ks_giveaways_captcha_site_key');
define('KS_GIVEAWAYS_OPTION_CAPTCHA_SECRET_KEY', 'ks_giveaways_captcha_secret_key');

define('KS_GIVEAWAYS_OPTION_LICENSE_KEY', 'ks_giveaways_license_key');
define('KS_GIVEAWAYS_OPTION_LICENSE_STATUS', 'ks_giveaways_license_status');
define('KS_GIVEAWAYS_OPTION_TWITTER_VIA', 'ks_giveaways_twitter_via');
define('KS_GIVEAWAYS_OPTION_FACEBOOK_PAGE', 'ks_giveaways_facebook_page_id');
define('KS_GIVEAWAYS_OPTION_EMAIL_FROM_ADDRESS', 'ks_giveaways_email_from_address');
define('KS_GIVEAWAYS_OPTION_EMAIL_REPLY_TO_ADDRESS', 'ks_giveaways_email_replyto_address');

define('KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUPPRESS', 'ks_giveaways_entry_email_suppress');
define('KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUBJECT', 'ks_giveaways_entry_email_subject');
define('KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_BODY', 'ks_giveaways_entry_email_body');

define('KS_GIVEAWAYS_OPTION_WINNER_EMAIL_SUBJECT', 'ks_giveaways_winner_email_subject');
define('KS_GIVEAWAYS_OPTION_WINNER_EMAIL_BODY', 'ks_giveaways_winner_email_body');

define('KS_GIVEAWAYS_OPTION_ADDRESS_STREET', 'ks_giveaways_address_street');
define('KS_GIVEAWAYS_OPTION_ADDRESS_CITY', 'ks_giveaways_address_city');
define('KS_GIVEAWAYS_OPTION_ADDRESS_STATE', 'ks_giveaways_address_state');
define('KS_GIVEAWAYS_OPTION_ADDRESS_COUNTRY', 'ks_giveaways_address_country');
define('KS_GIVEAWAYS_OPTION_ADDRESS_ZIP', 'ks_giveaways_address_zip');

define('KS_GIVEAWAYS_OPTION_EXTRA_FOOTER', 'ks_giveaways_extra_footer');
define('KS_GIVEAWAYS_OPTION_EXTRA_CONTESTANT_FOOTER', 'ks_giveaways_extra_contestant_footer');

define('KS_GIVEAWAYS_OPTION_AWEBER_LIST_ID', 'ks_giveaways_aweber_list_id');
define('KS_GIVEAWAYS_OPTION_AWEBER_KEY', 'ks_giveaways_aweber_key');
define('KS_GIVEAWAYS_OPTION_AWEBER_CONSUMER_KEY', 'ks_giveaways_aweber_consumer_key');
define('KS_GIVEAWAYS_OPTION_AWEBER_CONSUMER_SECRET', 'ks_giveaways_aweber_consumer_secret');
define('KS_GIVEAWAYS_OPTION_AWEBER_ACCESS_KEY', 'ks_giveaways_aweber_access_key');
define('KS_GIVEAWAYS_OPTION_AWEBER_ACCESS_SECRET', 'ks_giveaways_aweber_access_secret');

define('KS_GIVEAWAYS_OPTION_MAILCHIMP_LIST_ID', 'ks_giveaways_mailchimp_list_id');
define('KS_GIVEAWAYS_OPTION_MAILCHIMP_KEY', 'ks_giveaways_mailchimp_key');

define('KS_GIVEAWAYS_OPTION_GETRESPONSE_CAMPAIGN_ID', 'ks_giveaways_getresponse_campaign_id');
define('KS_GIVEAWAYS_OPTION_GETRESPONSE_KEY', 'ks_giveaways_getresponse_key');

define('KS_GIVEAWAYS_COOKIE_CONTESTANT', 'ks_giveaways_contestant');
define('KS_GIVEAWAYS_COOKIE_EMAIL_ADDRESS', 'ks_giveaways_email');

require_once(plugin_dir_path(__FILE__) . 'public/class-ks-giveaways.php');

register_activation_hook(__FILE__, array('KS_Giveaways', '_activate'));
register_deactivation_hook(__FILE__, array('KS_Giveaways', '_deactivate'));

add_action('plugins_loaded', array('KS_Giveaways', 'get_instance'));

// ensure we activate on multisite WP
add_action('wpmu_new_blog', 'ks_giveaways_new_blog', 10, 6);
function ks_giveaways_new_blog($blog_id, $user_id, $domain, $path, $site_id, $meta)
{
    global $wpdb;

    $plugin = plugin_basename(KS_GIVEAWAYS_PLUGIN_FILE);
    if (is_plugin_active_for_network($plugin)) {
        $old_blog = $wpdb->blogid;
        switch_to_blog($blog_id);
        KS_Giveaways::activate();
        switch_to_blog($old_blog);
    }
}

if (is_admin()) {
    require_once(plugin_dir_path(__FILE__) . 'admin/class-ks-giveaways-admin.php');
    add_action('plugins_loaded', array('KS_Giveaways_Admin', 'get_instance'));
}

