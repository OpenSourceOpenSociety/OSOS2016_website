<?php

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-winner-db.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-entry-db.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-helper.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-aweber.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-mailchimp.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-getresponse.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'wordpress-common' . DIRECTORY_SEPARATOR . 'class-ks-debug.php';

if (!class_exists('EDD_SL_Plugin_Updater')) {
    require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'wordpress-common' . DIRECTORY_SEPARATOR . 'EDD_SL_Plugin_Updater.php';
}

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'wordpress-common' . DIRECTORY_SEPARATOR . 'class-ks-http.php';

/**
 * @package     KS_Giveaways_Admin
 */
class KS_Giveaways_Admin
{
    /**
     * Instance of this class.
     */
    protected static $instance = null;

    private function __construct()
    {
        $plugin = KS_Giveaways::get_instance();
        $this->plugin_slug = $plugin->get_plugin_slug();

        add_action('admin_init', array($this, 'admin_init'));
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        add_action('plugin_action_links_ks-giveaways/ks-giveaways.php', array($this, 'add_plugin_links'));

        add_action('admin_notices', array($this, 'admin_notices'));
        add_action('current_screen', array($this, 'current_screen'));

        add_filter('manage_'.KS_GIVEAWAYS_POST_TYPE.'_posts_columns', array($this, 'set_contest_columns'), 99999);
        add_filter('manage_'.KS_GIVEAWAYS_POST_TYPE.'_posts_custom_column', array($this, 'display_contest_column'), 10, 2);
        add_filter('get_pages', array($this, 'add_giveaways_to_dropdown'), 10, 2);

        add_filter('bulk_post_updated_messages', array($this, 'bulk_post_updated_messages'));
        add_filter('post_updated_messages', array($this, 'post_updated_messages'));
        add_filter('redirect_post_location', array($this, 'redirect_post_location'), 10, 2);
        add_action('dbx_post_advanced', array($this, 'dbx_post_advanced'));
        add_filter('post_row_actions', array($this, 'set_page_row_actions'), 10, 2);
        add_filter('views_edit-'.KS_GIVEAWAYS_POST_TYPE, array($this, 'set_page_views'));
        add_filter('default_hidden_meta_boxes', array($this, 'default_hidden_meta_boxes'), 10, 2);
    }

    /**
     * Returns an instance of this class.
     *
     * @return  object    A single instance of this class.
     */
    public static function get_instance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function add_giveaways_to_dropdown($pages, $r)
    {
        if (isset($r['name']) && $r['name'] == 'page_on_front') {
            $args = array('post_type' => KS_GIVEAWAYS_POST_TYPE);
            $items = get_posts($args);
            if ($items) {
                $pages = array_merge($pages, $items);
            }
        }

        return $pages;
    }

    public function add_plugin_links($links)
    {
        $settings_link = sprintf('<a href="%s">Settings</a>', admin_url('options-general.php?page=ks-giveaways-options'));
        $promo_link = '<a href="http://kingsumo.com/" target="_blank" class="more-ks-plugins">More Plugins</a>';

        $links[] = $settings_link;
        $links[] = $promo_link;

        return $links;
    }

    /**
     * Register and enqueue the admin stylesheet.
     */
    public function enqueue_admin_styles()
    {
        wp_enqueue_style($this->plugin_slug . '-admin-styles', plugins_url('assets/css/admin.css', __FILE__), array(), KS_Giveaways::VERSION);
        wp_enqueue_style($this->plugin_slug . '-picker', plugins_url('assets/css/picker/default.css', __FILE__), array(), KS_Giveaways::VERSION);
        wp_enqueue_style($this->plugin_slug . '-picker-date', plugins_url('assets/css/picker/default.date.css', __FILE__), array(), KS_Giveaways::VERSION);
        wp_enqueue_style($this->plugin_slug . '-picker-time', plugins_url('assets/css/picker/default.time.css', __FILE__), array(), KS_Giveaways::VERSION);
        wp_enqueue_style('thickbox');
        wp_enqueue_style('media');
    }

    /**
     * Register and enqueue admin Javascript files.
     */
    public function enqueue_admin_scripts()
    {
        wp_enqueue_script($this->plugin_slug . '-legacy', plugins_url('assets/js/legacy.js', __FILE__), array('jquery'), KS_Giveaways::VERSION);
        wp_enqueue_script($this->plugin_slug . '-picker', plugins_url('assets/js/picker.js', __FILE__), array('jquery',$this->plugin_slug.'-legacy'), KS_Giveaways::VERSION);
        wp_enqueue_script($this->plugin_slug . '-picker-date', plugins_url('assets/js/picker.date.js', __FILE__), array('jquery',$this->plugin_slug.'-legacy'), KS_Giveaways::VERSION);
        wp_enqueue_script($this->plugin_slug . '-picker-time', plugins_url('assets/js/picker.time.js', __FILE__), array('jquery',$this->plugin_slug.'-legacy'), KS_Giveaways::VERSION);
        wp_enqueue_script($this->plugin_slug . '-excanvas', plugins_url('assets/js/excanvas.min.js', __FILE__), array('jquery'), KS_Giveaways::VERSION);
        wp_enqueue_script($this->plugin_slug . '-editable', plugins_url('assets/js/jquery.jeditable.min.js', __FILE__), array('jquery'), KS_Giveaways::VERSION);
        wp_enqueue_script($this->plugin_slug . '-flot', plugins_url('assets/js/jquery.flot.min.js', __FILE__), array('jquery',$this->plugin_slug . '-excanvas'), KS_Giveaways::VERSION);
        wp_enqueue_script($this->plugin_slug . '-flot-time', plugins_url('assets/js/jquery.flot.time.min.js', __FILE__), array('jquery',$this->plugin_slug . '-flot'), KS_Giveaways::VERSION);
        //wp_enqueue_script($this->plugin_slug . '-admin-script', plugins_url('assets/js/admin.js', __FILE__), array('jquery'), KS_Giveaways::VERSION);
        wp_enqueue_script('thickbox');
        wp_enqueue_media();
    }

    public function admin_init()
    {
        // upgrade checks
        KS_Giveaways::check_default_options();

        add_action('wp_ajax_ks_activate_giveaways_license', array($this, 'ajax_activate_license'));
        add_action('wp_ajax_ks_deactivate_giveaways_license', array($this, 'ajax_deactivate_license'));
        add_action('wp_ajax_ks_save_giveaways_winner_name', array($this, 'save_winner_name'));
        add_action('wp_ajax_ks_save_giveaways_winner_avatar', array($this, 'save_winner_avatar'));

        $this->updater = new EDD_SL_Plugin_Updater(
          KS_GIVEAWAYS_EDD_URL,
          KS_GIVEAWAYS_PLUGIN_DIR . DIRECTORY_SEPARATOR . 'ks-giveaways.php',
          array(
              'version' => KS_GIVEAWAYS_EDD_VERSION,
              'item_name' => KS_GIVEAWAYS_EDD_NAME,
              'author' => KS_GIVEAWAYS_EDD_AUTHOR,
              'license' => get_option(KS_GIVEAWAYS_OPTION_LICENSE_KEY)
          )
        );

        $tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : 'general';

        add_action('add_meta_boxes_'.KS_GIVEAWAYS_POST_TYPE, array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_contest'));

        if ($tab == 'general') {
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_LICENSE_KEY, array($this, 'sanitize_license'));
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_FACEBOOK_PAGE, array($this, 'sanitize_url'));
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_TWITTER_VIA, array($this, 'sanitize_twitter'));
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY, 'sanitize_text_field');
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_CAPTCHA_SECRET_KEY, 'sanitize_text_field');

            add_settings_section('ks_giveaways_license', 'License', null, 'ks-giveaways-options');
            add_settings_field(KS_GIVEAWAYS_OPTION_LICENSE_KEY, 'License Key', array($this, 'input_license_key'), 'ks-giveaways-options', 'ks_giveaways_license');

            add_settings_section('ks_giveaways_captcha', 'Google reCAPTCHA', null, 'ks-giveaways-options');
            add_settings_field(KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY, 'Site Key', array($this, 'input_captcha_site_key'), 'ks-giveaways-options', 'ks_giveaways_captcha');
            add_settings_field(KS_GIVEAWAYS_OPTION_CAPTCHA_SECRET_KEY, 'Secret Key', array($this, 'input_captcha_secret_key'), 'ks-giveaways-options', 'ks_giveaways_captcha');

            add_settings_section('ks_giveaways_social', 'Social', null, 'ks-giveaways-options');
            add_settings_field(KS_GIVEAWAYS_OPTION_FACEBOOK_PAGE, 'Facebook Page', array($this, 'input_facebook_page'), 'ks-giveaways-options', 'ks_giveaways_social');
            add_settings_field(KS_GIVEAWAYS_OPTION_TWITTER_VIA, 'Twitter Handle', array($this, 'input_twitter_via'), 'ks-giveaways-options', 'ks_giveaways_social');

        }

        if ($tab == 'email') {
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUPPRESS, '');
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUBJECT, 'sanitize_text_field');
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_BODY, '');
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_WINNER_EMAIL_SUBJECT, 'sanitize_text_field');
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_WINNER_EMAIL_BODY, '');
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_EMAIL_FROM_ADDRESS, '');
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_EMAIL_REPLY_TO_ADDRESS, '');

            add_settings_section('ks_giveaways_email_address', 'Email Addresses', null, 'ks-giveaways-options');
            add_settings_field(KS_GIVEAWAYS_OPTION_EMAIL_FROM_ADDRESS, 'From Address', array($this, 'input_email_from_address'), 'ks-giveaways-options', 'ks_giveaways_email_address');
            add_settings_field(KS_GIVEAWAYS_OPTION_EMAIL_REPLY_TO_ADDRESS, 'Reply-To Address', array($this, 'input_email_replyto_address'), 'ks-giveaways-options', 'ks_giveaways_email_address');

            add_settings_section('ks_giveaways_entry_email', 'Entry Email Template', null, 'ks-giveaways-options');
            add_settings_field(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUPPRESS, 'Suppress Entry Email', array($this, 'input_entry_email_suppress'), 'ks-giveaways-options', 'ks_giveaways_entry_email');
            add_settings_field(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUBJECT, 'Subject', array($this, 'input_entry_email_subject'), 'ks-giveaways-options', 'ks_giveaways_entry_email');
            add_settings_field(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_BODY, 'Body', array($this, 'input_entry_email_body'), 'ks-giveaways-options', 'ks_giveaways_entry_email');

            add_settings_section('ks_giveaways_winner_email', 'Winner Email Template', null, 'ks-giveaways-options');
            add_settings_field(KS_GIVEAWAYS_OPTION_WINNER_EMAIL_SUBJECT, 'Subject', array($this, 'input_winner_email_subject'), 'ks-giveaways-options', 'ks_giveaways_winner_email');
            add_settings_field(KS_GIVEAWAYS_OPTION_WINNER_EMAIL_BODY, 'Body', array($this, 'input_winner_email_body'), 'ks-giveaways-options', 'ks_giveaways_winner_email');
        }

        if ($tab == 'settings') {
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ADDRESS_STREET, 'sanitize_text_field');
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ADDRESS_CITY, 'sanitize_text_field');
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ADDRESS_STATE, 'sanitize_text_field');
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ADDRESS_COUNTRY, 'sanitize_text_field');
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_ADDRESS_ZIP, 'sanitize_text_field');

            add_settings_section('ks_giveaways_address', 'Location', null, 'ks-giveaways-options');
            add_settings_field(KS_GIVEAWAYS_OPTION_ADDRESS_STREET, 'Street', array($this, 'input_address_street'), 'ks-giveaways-options', 'ks_giveaways_address');
            add_settings_field(KS_GIVEAWAYS_OPTION_ADDRESS_CITY, 'City', array($this, 'input_address_city'), 'ks-giveaways-options', 'ks_giveaways_address');
            add_settings_field(KS_GIVEAWAYS_OPTION_ADDRESS_STATE, 'State', array($this, 'input_address_state'), 'ks-giveaways-options', 'ks_giveaways_address');
            add_settings_field(KS_GIVEAWAYS_OPTION_ADDRESS_COUNTRY, 'Country', array($this, 'input_address_country'), 'ks-giveaways-options', 'ks_giveaways_address');
            add_settings_field(KS_GIVEAWAYS_OPTION_ADDRESS_ZIP, 'Postal Code', array($this, 'input_address_zip'), 'ks-giveaways-options', 'ks_giveaways_address');
        }

        if ($tab == 'advanced') {
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_EXTRA_FOOTER, '');
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_EXTRA_CONTESTANT_FOOTER, '');

            add_settings_section('ks_giveaways_template', 'Template', null, 'ks-giveaways-options');
            add_settings_field(KS_GIVEAWAYS_OPTION_EXTRA_FOOTER, 'Extra Footer', array($this, 'input_extra_footer'), 'ks-giveaways-options', 'ks_giveaways_template');
            add_settings_field(KS_GIVEAWAYS_OPTION_EXTRA_CONTESTANT_FOOTER, 'Extra Contestant Footer', array($this, 'input_extra_contestant_footer'), 'ks-giveaways-options', 'ks_giveaways_template');
        }

        if ($tab == 'services') {
            register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_AWEBER_KEY, array($this, 'sanitize_aweber_key'));

            add_settings_section('ks_giveaways_aweber', 'Aweber', null, 'ks-giveaways-options');

            if (KS_Giveaways_Aweber::is_valid()) {
                add_settings_field(KS_GIVEAWAYS_OPTION_AWEBER_KEY, 'Disconnect from API', array($this, 'disconnect_aweber_button'), 'ks-giveaways-options', 'ks_giveaways_aweber');

                register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_AWEBER_LIST_ID, '');
                add_settings_field(KS_GIVEAWAYS_OPTION_AWEBER_LIST_ID, 'List', array($this, 'input_aweber_list_id'), 'ks-giveaways-options', 'ks_giveaways_aweber');
            } else {
                add_settings_field(KS_GIVEAWAYS_OPTION_AWEBER_KEY, 'Connect to API', array($this, 'input_aweber_key'), 'ks-giveaways-options', 'ks_giveaways_aweber');
            }

            add_settings_section('ks_giveaways_mailchimp', 'MailChimp', null, 'ks-giveaways-options');
            if (KS_Giveaways_Mailchimp::is_valid()) {
                add_settings_field(KS_GIVEAWAYS_OPTION_MAILCHIMP_KEY, 'Disconnect from API', array($this, 'disconnect_mailchimp_button'), 'ks-giveaways-options', 'ks_giveaways_mailchimp');

                register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_MAILCHIMP_LIST_ID, '');
                add_settings_field(KS_GIVEAWAYS_OPTION_MAILCHIMP_LIST_ID, 'List', array($this, 'input_mailchimp_list_id'), 'ks-giveaways-options', 'ks_giveaways_mailchimp');
            } else {
                register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_MAILCHIMP_KEY, array($this, 'sanitize_mailchimp_key'));
                add_settings_field(KS_GIVEAWAYS_OPTION_MAILCHIMP_KEY, 'Connect to API', array($this, 'input_mailchimp_key'), 'ks-giveaways-options', 'ks_giveaways_mailchimp');
            }

            // GetResponse API
            add_settings_section('ks_giveaways_getresponse', 'GetResponse', null, 'ks-giveaways-options');
            if (KS_Giveaways_GetResponse::is_valid()) {
                add_settings_field(KS_GIVEAWAYS_OPTION_GETRESPONSE_KEY, 'Disconnect from API', array($this, 'disconnect_getresponse_button'), 'ks-giveaways-options', 'ks_giveaways_getresponse');

                register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_GETRESPONSE_CAMPAIGN_ID, '');
                add_settings_field(KS_GIVEAWAYS_OPTION_GETRESPONSE_CAMPAIGN_ID, 'List', array($this, 'input_getresponse_campaign_id'), 'ks-giveaways-options', 'ks_giveaways_getresponse');
            } else {
                register_setting('ks_giveaways_options', KS_GIVEAWAYS_OPTION_GETRESPONSE_KEY, array($this, 'sanitize_getresponse_key'));
                add_settings_field(KS_GIVEAWAYS_OPTION_GETRESPONSE_KEY, 'Connect to API', array($this, 'input_getresponse_key'), 'ks-giveaways-options', 'ks_giveaways_getresponse');
            }
        }
    }

    public function disconnect_aweber_button()
    {
        echo '<a href="'.admin_url('admin.php?page=ks-giveaways&action=disconnect-aweber&noheader=true').'" class="button">Disconnect Aweber API</a>';
    }

    public function disconnect_mailchimp_button()
    {
        echo '<a href="'.admin_url('admin.php?page=ks-giveaways&action=disconnect-mailchimp&noheader=true').'" class="button">Disconnect MailChimp API</a>';
    }

    public function disconnect_getresponse_button()
    {
        echo '<a href="'.admin_url('admin.php?page=ks-giveaways&action=disconnect-getresponse&noheader=true').'" class="button">Disconnect GetResponse API</a>';
    }

    public function sanitize_aweber_key($key)
    {
        if ($key) {
            try {
                $auth = KS_Giveaways_Aweber::auth_from_key($key);

                update_option(KS_GIVEAWAYS_OPTION_AWEBER_CONSUMER_KEY, $auth[0]);
                update_option(KS_GIVEAWAYS_OPTION_AWEBER_CONSUMER_SECRET, $auth[1]);
                update_option(KS_GIVEAWAYS_OPTION_AWEBER_ACCESS_KEY, $auth[2]);
                update_option(KS_GIVEAWAYS_OPTION_AWEBER_ACCESS_SECRET, $auth[3]);

                delete_transient('ks_giveaways_aweber_lists');
            }
            catch(Exception $e)
            {
            }

            delete_option(KS_GIVEAWAYS_OPTION_AWEBER_KEY);
        }

        return '';
    }

    public function save_winner_avatar()
    {
        $url = isset($_POST['value']) ? $_POST['value'] : null;
        $id = isset($_POST['id']) ? (int) str_replace('winner_avatar_', '', $_POST['id']) : null;

        if ($id) {
            if ($url !== null) {
                KS_Winner_DB::update_avatar($id, $url);
            }

            $winner = KS_Winner_DB::get($id);
            if ($winner) {
                $url = $winner->winner_avatar;
                if (!$url) {
                    $url = plugins_url('assets/images/user-avatar.jpg', KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR);
                }
                echo $url;
            }
        }
        exit;
    }

    public function save_winner_name()
    {
        $name = isset($_POST['value']) ? $_POST['value'] : null;
        $id = isset($_POST['id']) ? (int) str_replace('winner_name_', '', $_POST['id']) : null;

        if ($id) {
            if ($name !== null) {
                KS_Winner_DB::update_name($id, $name);
            }

            $winner = KS_Winner_DB::get($id);
            if ($winner) {
                echo $winner->winner_name;
            }
        }
        exit;
    }

    public function sanitize_mailchimp_key($key)
    {
        $key = trim($key);

        if ($key) {
            try {
                delete_transient('ks_giveaways_mailchimp_lists');
            }
            catch(Exception $e)
            {
            }
        }

        return $key;
    }

    public function sanitize_getresponse_key($key)
    {
        $key = trim($key);

        if ($key) {
            try {
                delete_transient('ks_giveaways_getresponse_campaigns');
            }
            catch(Exception $e)
            {
            }
        }

        return $key;
    }

    public function sanitize_url($url)
    {
        $url = sanitize_text_field($url);
        if ($url) {
            $parts = parse_url($url);
            if (!is_array($parts) || !isset($parts['scheme'])) {
                $url = 'http://' . $url;
                $parts = parse_url($url);
                if (!is_array($parts) || !isset($parts['scheme']) || !filter_var($url, FILTER_VALIDATE_URL)) {
                    $url = '';
                }
            }
        }

        return $url;
    }

    public function sanitize_twitter($handle)
    {
        $handle = ltrim(sanitize_text_field($handle), '@');

        return $handle;
    }

    public function admin_menu()
    {
        add_options_page('KingSumo Giveaways', 'KingSumo Giveaways', 'manage_options', 'ks-giveaways-options', array($this, 'settings_page'));
        add_submenu_page(null, 'KingSumo Giveaways', null, 'manage_options', 'ks-giveaways', array($this, 'giveaways_page'), plugin_dir_url(__FILE__) . '/assets/images/ks-giveaways-icon-wp-solid.png');
    }

    public function settings_page()
    {
        $active_tab = isset($_REQUEST['tab']) ? $_REQUEST['tab'] : 'general';

        require_once KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'settings.php';
    }

    private function display_input_box($name, $value, $description = null, $type = 'text', $cls = 'regular-text')
    {
        echo sprintf('<input type="%s" class="%s" name="%s" value="%s" />'."\r\n", $type, $cls, $name, esc_attr($value));
        if ($description) {
            echo '<p class="description">' . $description . '</p>'."\r\n";
        }
    }

    private function display_textarea_box($name, $value, $description = null, $rows = 5, $cls = 'regular-text')
    {
        echo sprintf('<textarea rows="%s" class="%s" name="%s" style="width:85%%;">%s</textarea>'."\r\n", $rows, $cls, $name, esc_textarea($value));
        if ($description) {
            echo '<p class="description">' . $description . '</p>'."\r\n";
        }
    }

    private function display_select($name, $value, $options = array(), $description = null)
    {
        $opts = array();
        if ($options) {
            foreach ($options as $key => $val) {
                $opts[] = sprintf('<option value="%s"%s>%s</option>', $key, $key == $value ? ' selected' : '', $val);
            }
        }
        echo sprintf('<select name="%s">%s</select>', $name, implode("\n", $opts));
        if ($description) {
            echo '<p class="description">' . $description . '</p>'."\r\n";
        }
    }

    public function input_extra_footer()
    {
        $this->display_textarea_box(KS_GIVEAWAYS_OPTION_EXTRA_FOOTER, get_option(KS_GIVEAWAYS_OPTION_EXTRA_FOOTER), 'Extra code appended to the footer of every giveaway.  This can be used to place Google Analytics or any other tracking code.');
    }

    public function input_extra_contestant_footer()
    {
        $this->display_textarea_box(KS_GIVEAWAYS_OPTION_EXTRA_CONTESTANT_FOOTER, get_option(KS_GIVEAWAYS_OPTION_EXTRA_CONTESTANT_FOOTER), 'Additional extra code appended to the footer for contestants who have entered.  This can be used to track conversions.');
    }

    public function input_aweber_list_id()
    {
        $options = array('' => '-- Don\'t automatically subscribe contestants to Aweber --');

        $lists = get_transient('ks_giveaways_aweber_lists');
        if ($lists === false) {
            $cls = KS_Giveaways_Aweber::get_instance();
            $lists = $cls->get_lists();
            if (is_array($lists)) {
                set_transient('ks_giveaways_aweber_lists', $lists, 1 * MINUTE_IN_SECONDS);
            }
        }

        if ($lists && is_array($lists)) {
            $options = array_merge($options, $lists);
        }

        $this->display_select(KS_GIVEAWAYS_OPTION_AWEBER_LIST_ID, get_option(KS_GIVEAWAYS_OPTION_AWEBER_LIST_ID), $options, 'The Aweber subscriber list contestant email addresses will be automatically added to.');
    }

    public function input_mailchimp_list_id()
    {
        $options = array('' => '-- Don\'t automatically subscribe contestants to Mailchimp --');

        $lists = get_transient('ks_giveaways_mailchimp_lists');
        if ($lists === false) {
            $cls = KS_Giveaways_Mailchimp::get_instance();
            $lists = $cls->get_lists();
            if (is_array($lists)) {
                set_transient('ks_giveaways_mailchimp_lists', $lists, 1 * MINUTE_IN_SECONDS);
            }
        }

        if ($lists && is_array($lists)) {
            $options = array_merge($options, $lists);
        }

        $this->display_select(KS_GIVEAWAYS_OPTION_MAILCHIMP_LIST_ID, get_option(KS_GIVEAWAYS_OPTION_MAILCHIMP_LIST_ID), $options, 'The Mailchimp subscriber list contestant email addresses will be automatically added to.');
    }

    public function input_getresponse_campaign_id()
    {
        $options = array('' => '-- Don\'t automatically subscribe contestants to GetResponse --');

        $campaigns = get_transient('ks_giveaways_getresponse_campaigns');
        if ($campaigns === false) {
            /** @var KS_Giveaways_GetResponse $cls */
            $cls = KS_Giveaways_GetResponse::get_instance();
            $campaigns = $cls->get_campaigns();
            if (is_array($campaigns)) {
                set_transient('ks_giveaways_getresponse_campaigns', $campaigns, 1 * MINUTE_IN_SECONDS);
            }
        }

        if ($campaigns && is_array($campaigns)) {
            $options = array_merge($options, $campaigns);
        }

        $this->display_select(KS_GIVEAWAYS_OPTION_GETRESPONSE_CAMPAIGN_ID, get_option(KS_GIVEAWAYS_OPTION_GETRESPONSE_CAMPAIGN_ID), $options, 'The GetResponse subscriber campaign contestant email addresses will be automatically added to.');
    }

    public function input_aweber_key()
    {
        $this->display_input_box(KS_GIVEAWAYS_OPTION_AWEBER_KEY, '', 'To authenticate with Aweber <a href="https://auth.aweber.com/1.0/oauth/authorize_app/984c4ccd" id="ks_giveaways_aweber_auth" target="_blank">click here to get your Aweber code</a>, paste it in to the box above and click Save Changes.');
    }

    public function input_mailchimp_key()
    {
        $this->display_input_box(KS_GIVEAWAYS_OPTION_MAILCHIMP_KEY, get_option(KS_GIVEAWAYS_OPTION_MAILCHIMP_KEY), 'To authenticate with MailChimp <a href="http://kb.mailchimp.com/article/where-can-i-find-my-api-key" target="_blank">click here to learn how to generate an API key</a>, paste it in to the box above and click Save Changes.');
    }

    public function input_captcha_site_key()
    {
        $this->display_input_box(KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY, get_option(KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY), '<a href="https://www.google.com/recaptcha/admin" target="_blank">Sign up for a Google reCAPTCHA site key</a> to use captcha verification on giveaways.');
    }

    public function input_captcha_secret_key()
    {
        $this->display_input_box(KS_GIVEAWAYS_OPTION_CAPTCHA_SECRET_KEY, get_option(KS_GIVEAWAYS_OPTION_CAPTCHA_SECRET_KEY));
    }

    public function input_getresponse_key()
    {
        $this->display_input_box(KS_GIVEAWAYS_OPTION_GETRESPONSE_KEY, get_option(KS_GIVEAWAYS_OPTION_GETRESPONSE_KEY), 'To authenticate with GetResponse <a href="http://support.getresponse.com/faq/where-i-find-api-key" target="_blank">click here to learn how to generate an API key</a>, paste it in to the box above and click Save Changes.');
    }

    public function input_address_street()
    {
        $this->display_input_box(KS_GIVEAWAYS_OPTION_ADDRESS_STREET, get_option(KS_GIVEAWAYS_OPTION_ADDRESS_STREET), 'Street address for the [address_street] short code.');
    }

    public function input_address_city()
    {
        $this->display_input_box(KS_GIVEAWAYS_OPTION_ADDRESS_CITY, get_option(KS_GIVEAWAYS_OPTION_ADDRESS_CITY), 'City used for the [address_city] short code.');
    }

    public function input_address_state()
    {
        $this->display_input_box(KS_GIVEAWAYS_OPTION_ADDRESS_STATE, get_option(KS_GIVEAWAYS_OPTION_ADDRESS_STATE), 'State used for the [address_state] short code.');
    }

    public function input_address_country()
    {
        $this->display_input_box(KS_GIVEAWAYS_OPTION_ADDRESS_COUNTRY, get_option(KS_GIVEAWAYS_OPTION_ADDRESS_COUNTRY), 'Country used for the [address_country] short code.');
    }

    public function input_address_zip()
    {
        $this->display_input_box(KS_GIVEAWAYS_OPTION_ADDRESS_ZIP, get_option(KS_GIVEAWAYS_OPTION_ADDRESS_ZIP), 'Postal code used for the [address_zip] short code.');
    }

    public function input_email_from_address()
    {
        echo sprintf('<input type="text" class="regular-text" name="ks_giveaways_email_from_address" value="%s" placeholder="'. get_bloginfo('name') . ' &lt;'.get_bloginfo('admin_email').'&gt;" />', esc_attr(get_option(KS_GIVEAWAYS_OPTION_EMAIL_FROM_ADDRESS)));
        echo '<p class="description">Email address to send emails from.</p>';
    }

    public function input_email_replyto_address()
    {
        echo sprintf('<input type="text" class="regular-text" name="ks_giveaways_email_replyto_address" value="%s" placeholder="" />', esc_attr(get_option(KS_GIVEAWAYS_OPTION_EMAIL_REPLY_TO_ADDRESS)));
        echo '<p class="description">Reply-to address for emails.  Defaults to From address if blank.</p>';
    }

    public function input_entry_email_suppress()
    {
        echo sprintf('<input type="checkbox" id="ks_giveaways_entry_email_suppress" name="ks_giveaways_entry_email_suppress" value="1"%s />', get_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUPPRESS) ? ' checked' : '');
        echo '<label for="ks_giveaways_entry_email_suppress">Don\'t send confirmation emails to contestants</label>';
        echo '<p class="description">Warning: By suppressing the entry email from being sent to contestants confirmation will not occur.</p>';
    }

    public function input_entry_email_subject()
    {
        echo sprintf('<input type="text" class="regular-text" name="ks_giveaways_entry_email_subject" value="%s" placeholder="" />', esc_attr(get_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUBJECT)));
        echo '<p class="description">Email subject notifying contestant of their successful entry to the giveaway.</p>';
    }

    public function input_entry_email_body()
    {
        wp_editor(get_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_BODY), 'ks_giveaways_entry_email_body', array(
            'wpautop' => true,
            'textarea_rows' => 10
        ));
        echo '<p class="description">';
        echo $this->get_shortcodes();
        echo '</p>';
    }

    public function input_winner_email_subject()
    {
        echo sprintf('<input type="text" class="regular-text" name="ks_giveaways_winner_email_subject" value="%s" placeholder="" />', esc_attr(get_option(KS_GIVEAWAYS_OPTION_WINNER_EMAIL_SUBJECT)));
        echo '<p class="description">Email subject notifying contestant they have won the giveaway.</p>';
    }

    public function input_winner_email_body()
    {
        wp_editor(get_option(KS_GIVEAWAYS_OPTION_WINNER_EMAIL_BODY), 'ks_giveaways_winner_email_body', array(
            'wpautop' => true,
            'textarea_rows' => 10
        ));
        echo '<p class="description">';
        echo $this->get_shortcodes();
        echo '</p>';
    }

    private function get_shortcodes()
    {
        $settings_url = admin_url('options-general.php?page=ks-giveaways-options&tab=settings');

        return <<<EOF
          <a href="javascript:void(0)" onclick="jQuery(this).next().toggle();">Toggle available shortcodes</a>
          <span style="display:none">
            <br />
            <strong>[name]</strong> Name of the giveaway<br />
            <strong>[site_name]</strong> Name of the WordPress website<br />
            <strong>[prize_name]</strong> Name of the prize<br />
            <strong>[prize_brand]</strong> Brand of the prize<br />
            <strong>[prize_value]</strong> Value of the prize<br />
            <strong>[date_ended]</strong> Date the giveaway ends<br />
            <strong>[date_awarded]</strong> Date the prize is awarded<br />
            <strong>[contact_email]</strong> Contact email address from the WordPress website<br />
            <strong>[site_url]</strong> URL of the WordPress website<br />
            <strong>[lucky_url]</strong> Lucky URL of the current contestant<br />
            <strong>[confirm_url]</strong> Confirm email URL for the current contestant<br />
            <strong>[entries_per_friend]</strong> Number of entries per referral<br />
            <strong>[address_street]</strong> Street address from <a href="{$settings_url}">giveaway settings</a><br />
            <strong>[address_city]</strong> City from <a href="{$settings_url}">giveaway settings</a><br />
            <strong>[address_state]</strong> State from <a href="{$settings_url}">giveaway settings</a><br />
            <strong>[address_country]</strong> Country from <a href="{$settings_url}">giveaway settings</a><br />
            <strong>[address_zip]</strong> Postal code from <a href="{$settings_url}">giveaway settings</a><br />
          </span>
EOF;
    }

    public function input_twitter_via()
    {
        echo sprintf('<input type="text" class="regular-text" name="ks_giveaways_twitter_via" value="%s" placeholder="KingSumo" />', esc_attr(get_option(KS_GIVEAWAYS_OPTION_TWITTER_VIA)));
        echo '<p class="description">Your Twitter @(Handle) will be used for "via" on share messages and for the Twitter follow button.</p>';
    }

    public function input_facebook_page()
    {
        echo sprintf('<input type="text" class="regular-text" name="ks_giveaways_facebook_page_id" value="%s" placeholder="http://www.facebook.com/mygreatbusiness" />', esc_attr(get_option(KS_GIVEAWAYS_OPTION_FACEBOOK_PAGE)));
        echo '<p class="description">Enter a website or Facebook URL for the Facebook like button on the giveaway page.</p>';
    }

    public function input_license_key()
    {
        echo sprintf('<input type="text" class="regular-text" name="ks_giveaways_license_key" value="%s" />', get_option(KS_GIVEAWAYS_OPTION_LICENSE_KEY));
        echo '<span id="ks-license-container">';
        include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'license_status.php';
        echo '</span>';
    }

    public function ajax_activate_license()
    {
        $errors = array();
        $this->activate_license(get_option(KS_GIVEAWAYS_OPTION_LICENSE_KEY), 'activate_license', $errors);

        include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'license_status.php';
        exit;
    }

    public function ajax_deactivate_license()
    {
        $errors = array();
        $this->activate_license(get_option(KS_GIVEAWAYS_OPTION_LICENSE_KEY), 'deactivate_license');

        include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'license_status.php';
        exit;
    }

    public function activate_license($license, $action = 'activate_license', &$errors = null)
    {
        if ($license) {
            $api_params = array(
                'edd_action'=> $action,
                'license'     => $license,
                'item_name' => urlencode(KS_GIVEAWAYS_EDD_NAME)
            );

            $response = KS_Http::get(add_query_arg($api_params, KS_GIVEAWAYS_EDD_URL), $errors);

            if ($response === false) {
                return false;
            }

            $license_data = json_decode($response);
            if ($license_data === null) {
                if ($errors !== null) {
                    $errors[] = sprintf('Unable to JSON decode response: %s...', esc_html(substr($response, 0, 50)));
                }
                return false;
            }

            if (isset($license_data->license)) {
                $errors = null;
                update_option(KS_GIVEAWAYS_OPTION_LICENSE_STATUS, $license_data->license);
            }
        }
    }

    public function sanitize_license($new)
    {
        $old = get_option(KS_GIVEAWAYS_OPTION_LICENSE_KEY);
        if ($old != $new) {
            delete_option(KS_GIVEAWAYS_OPTION_LICENSE_STATUS);

            if (trim($new)) {
                $this->activate_license($new);
            }
        }

        return $new;
    }

    public function save_contest($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!isset($_POST['post_type']) || $_POST['post_type'] != KS_GIVEAWAYS_POST_TYPE || !$this->has_valid_license()) {
            return;
        }

        $offset = get_option('gmt_offset')*3600;
        foreach (array('start', 'end', 'awarded') as $key) {
            if (isset($_POST['date_' . $key])) {
                $time_start = isset($_POST['time_' . $key]) ? $_POST['time_' . $key] : '00:00';
                $parse = sprintf('%s %s:00+00:00', $_POST['date_' . $key], $time_start);
                $date_start = strtotime($parse) - $offset;
                update_post_meta($post_id, '_date_' . $key, $date_start);
            }
        }

        $contest_rules = $_POST['contest_rules'];
        update_post_meta($post_id, '_contest_rules', wpautop(wptexturize($contest_rules)));

        $contest_description = $_POST['contest_description'];
        update_post_meta($post_id, '_contest_description', wpautop(wptexturize($contest_description)));

        $prize_name = stripslashes_deep(sanitize_text_field($_POST['prize_name']));
        $prize_brand = stripslashes_deep(sanitize_text_field($_POST['prize_brand']));
        $prize_image = stripslashes_deep(sanitize_text_field($_POST['prize_image']));
        $prize_value = stripslashes_deep(sanitize_text_field($_POST['prize_value']));
        $winner_count = (int) stripslashes_deep(sanitize_text_field($_POST['winner_count']));
        if (!$winner_count) {
            $winner_count = 1;
        }

        $entries_per_friend = (int) stripslashes_deep(sanitize_text_field($_POST['entries_per_friend']));
        if (!$entries_per_friend) {
            $entries_per_friend = 3;
        }

        update_post_meta($post_id, '_prize_name', $prize_name);
        update_post_meta($post_id, '_prize_brand', $prize_brand);
        update_post_meta($post_id, '_prize_value', $prize_value);
        update_post_meta($post_id, '_prize_image', $prize_image);
        update_post_meta($post_id, '_winner_count', $winner_count);
        update_post_meta($post_id, '_entries_per_friend', $entries_per_friend);

        $templates = KS_Giveaways::get_available_templates();
        $template_file = $_POST['template_file'];
        if (!in_array($template_file, array_keys($templates))) {
            $template_file = KS_Giveaways::$default_template;
        }

        $logo_image = stripslashes_deep(sanitize_text_field($_POST['logo_image']));
        $background_image = stripslashes_deep(sanitize_text_field($_POST['background_image']));
        $image1 = stripslashes_deep(sanitize_text_field($_POST['image1']));
        $image1_link = stripslashes_deep(sanitize_text_field($_POST['image1_link']));
        $image2 = stripslashes_deep(sanitize_text_field($_POST['image2']));
        $image2_link = stripslashes_deep(sanitize_text_field($_POST['image2_link']));
        $image3 = stripslashes_deep(sanitize_text_field($_POST['image3']));
        $image3_link = stripslashes_deep(sanitize_text_field($_POST['image3_link']));

        update_post_meta($post_id, '_template_file', $template_file);
        update_post_meta($post_id, '_logo_image', $logo_image);
        update_post_meta($post_id, '_background_image', $background_image);
        update_post_meta($post_id, '_image_1', $image1);
        update_post_meta($post_id, '_image_1_link', $image1_link);
        update_post_meta($post_id, '_image_2', $image2);
        update_post_meta($post_id, '_image_2_link', $image2_link);
        update_post_meta($post_id, '_image_3', $image3);
        update_post_meta($post_id, '_image_3_link', $image3_link);

        $question = stripslashes_deep(sanitize_text_field($_POST['question']));
        $wrong_answer1 = stripslashes_deep(sanitize_text_field($_POST['wrong_answer1']));
        $wrong_answer2 = stripslashes_deep(sanitize_text_field($_POST['wrong_answer2']));
        $right_answer = stripslashes_deep(sanitize_text_field($_POST['right_answer']));

        update_post_meta($post_id, '_question', $question);
        update_post_meta($post_id, '_wrong_answer1', $wrong_answer1);
        update_post_meta($post_id, '_wrong_answer2', $wrong_answer2);
        update_post_meta($post_id, '_right_answer', $right_answer);

    }

    public function bulk_post_updated_messages($bulk_messages)
    {
        global $bulk_counts;

        $bulk_messages[KS_GIVEAWAYS_POST_TYPE] = array(
          'updated'   => _n( '%s giveaway updated.', '%s giveaways updated.', $bulk_counts['updated'] ),
          'locked'    => _n( '%s giveaway not updated, somebody is editing it.', '%s giveaways not updated, somebody is editing them.', $bulk_counts['locked'] ),
          'deleted'   => _n( '%s giveaway permanently deleted.', '%s giveaways permanently deleted.', $bulk_counts['deleted'] ),
          'trashed'   => _n( '%s giveaway moved to the Trash.', '%s giveaways moved to the Trash.', $bulk_counts['trashed'] ),
          'untrashed' => _n( '%s giveaway restored from the Trash.', '%s giveaways restored from the Trash.', $bulk_counts['untrashed'] ),
        );

        return $bulk_messages;
    }

    public function post_updated_messages($messages)
    {
        global $post_ID, $post;

        $messages[KS_GIVEAWAYS_POST_TYPE] = array(
           0 => '', // Unused. Messages start at index 1.
           1 => sprintf( __('Giveaway updated. <a href="%s">View giveaway</a>'), esc_url( get_permalink($post_ID) ) ),
           2 => __('Custom field updated.'),
           3 => __('Custom field deleted.'),
           4 => __('Giveaway updated.'),
          /* translators: %s: date and time of the revision */
           5 => isset($_GET['revision']) ? sprintf( __('Giveaway restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
           6 => sprintf( __('Giveaway published. <a href="%s">View giveaway</a>'), esc_url( get_permalink($post_ID) ) ),
           7 => __('Giveaway saved.'),
           8 => sprintf( __('Giveaway submitted. <a target="_blank" href="%s">Preview giveaway</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
           9 => sprintf( __('Giveaway scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview giveaway</a>'),
                  /* translators: Publish box date format, see http://php.net/date */
                  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
          10 => sprintf( __('Giveaway draft updated. <a target="_blank" href="%s">Preview giveaway</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        );

        return $messages;
    }

    public function redirect_post_location($location, $post_id)
    {
        if ((isset($_POST['save']) || isset($_POST['publish'])) && $_POST['post_type'] == KS_GIVEAWAYS_POST_TYPE) {
            $status = get_post_status($post_id);

            // if user is attempting to publish giveaway we validate all fields are filled in
            if ($status == 'publish' && !KS_Helper::validate_giveaway($post_id)) {
                remove_action('save_post', array($this, 'save_contest'));
                wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));
                add_action('save_post', array($this, 'save_contest'));

                // remove "Post published" message
                if (strpos($location, 'message=6') !== false || strpos($location, 'message=1') !== false) {
                    $location = remove_query_arg('message', $location);
                }

                // add error message to query string
                return add_query_arg('error_message', 1, $location);
            }

            // If user is attempting to publish giveaway, we validate that provided links are correct, if they're given
            if ($status == 'publish' && !KS_Helper::validate_giveaway_imagelinks($post_id)) {
                remove_action('save_post', array($this, 'save_contest'));
                wp_update_post(array('ID' => $post_id, 'post_status' => 'draft'));
                add_action('save_post', array($this, 'save_contest'));

                // remove "Post published" message
                if (strpos($location, 'message=6') !== false || strpos($location, 'message=1') !== false) {
                    $location = remove_query_arg('message', $location);
                }

                // add error message to query string
                return add_query_arg('error_message', 2, $location);
            }
        }

        return $location;
    }

    public function dbx_post_advanced()
    {
        if (get_post_type() != KS_GIVEAWAYS_POST_TYPE) {
            return;
        }

        global $notice;

        $error_messages = array(
            1 => __('Cannot publish giveaway until all fields are completed.'),
            2 => __('Cannot publish giveaway because provided image link URLs are not valid.')
        );

        if (isset($_GET['error_message']) && isset($error_messages[$_GET['error_message']])) {
            $notice = $error_messages[$_GET['error_message']];
        }
    }

    public function giveaways_page()
    {
        if (!$this->has_valid_license()) {
            return;
        }

        $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : null;

        switch($action) {
            case 'view': return $this->view_giveaway_page();
            case 'contestants': return $this->contestants_page();
            case 'disconnect-aweber':
                delete_transient('ks_giveaways_aweber_lists');
                KS_Giveaways_Aweber::disconnect();
                wp_redirect(admin_url('options-general.php?page=ks-giveaways-options&tab=services'));
                exit;
            case 'disconnect-mailchimp':
                delete_transient('ks_giveaways_mailchimp_lists');
                KS_Giveaways_Mailchimp::disconnect();
                wp_redirect(admin_url('options-general.php?page=ks-giveaways-options&tab=services'));
                exit;
            case 'disconnect-getresponse':
                delete_transient('ks_giveaways_getresponse_campaigns');
                KS_Giveaways_GetResponse::disconnect();
                wp_redirect(admin_url('options-general.php?page=ks-giveaways-options&tab=services'));
                exit;
        }
    }

    public function view_giveaway_page()
    {
        $id = $_REQUEST['id'];

        $GLOBALS['post'] = (int) $id;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post_action = $_POST['post_action'];

            switch($post_action) {
                case 'confirm':
                    $winner_id = $_POST['winner_id'];
                    KS_Winner_DB::update_status($winner_id);
                    break;

                case 'redraw':
                    $winner_id = $_POST['winner_id'];
                    KS_Entry_DB::draw($id, $winner_id);
                    break;

                case 'remove':
                    $winner_id = $_POST['winner_id'];
                    KS_Winner_DB::remove($winner_id);
                    break;

                case 'draw':
                    KS_Entry_DB::draw($id);
                    break;

                case 'notify':
                    $winner_id = $_POST['winner_id'];
                    KS_Winner_DB::notify($winner_id);
                    break;
            }
        }

        require_once KS_GIVEAWAYS_PLUGIN_ADMIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-ks-winners-list-table.php';

        $list_table = new KS_Winners_List_Table(array(
            'contest_id' => $id
        ));

        switch($list_table->current_action()) {
            case 'downloadcsv':
                KS_Winner_DB::output_csv($id);
                break;
        }

        $list_table->prepare_items();

        require_once KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'view.php';
    }

    public function contestants_page()
    {
        $id = $_REQUEST['id'];

        $GLOBALS['post'] = (int) $id;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $post_action = $_POST['post_action'];

            switch($post_action) {
                case 'remove':
                    $contestant_id = (int) $_POST['contestant_id'];
                    KS_Contestant_DB::remove($contestant_id);
                    break;

                case 'resend':
                    $contestant_id = (int) $_POST['contestant_id'];
                    $contestant = KS_Contestant_DB::get($contestant_id);
                    if ($contestant) {
                        KS_Helper::send_confirm_email($contestant);
                    }
                    break;
            }
        }

        require_once KS_GIVEAWAYS_PLUGIN_ADMIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-ks-contestants-list-table.php';

        $list_table = new KS_Contestants_List_Table(array(
            'contest_id' => $id
        ));

        switch($list_table->current_action()) {
            case 'downloadcsv':
                KS_Contestant_DB::output_csv($id);
                break;
        }

        $list_table->prepare_items();

        require_once KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'contestants.php';
    }

    public function add_meta_boxes($post_type)
    {
        global $wp_meta_boxes;

        // remove all unwanted meta boxes
        foreach (array_keys($wp_meta_boxes) as $screen) {
            foreach (array_keys($wp_meta_boxes[$screen]) as $context) {
                foreach (array_keys($wp_meta_boxes[$screen][$context]) as $priority) {
                    foreach (array_keys($wp_meta_boxes[$screen][$context][$priority]) as $id) {
                        if (in_array($id, array('submitdiv', 'slugdiv'))) {
                            continue;
                        }
                        remove_meta_box($id, $screen, $context);
                    }
                }
            }
        }

        add_meta_box('ks_contest_info', __('Step 1 &mdash; Giveaway Information'), array($this, 'info_meta_box'), KS_GIVEAWAYS_POST_TYPE, 'normal', 'high');
        add_meta_box('ks_contest_prize', __('Step 2 &mdash; Prize Information'), array($this, 'prize_meta_box'), KS_GIVEAWAYS_POST_TYPE, 'normal', 'default');
        add_meta_box('ks_contest_question', __('Step 3 &mdash; Question'), array($this, 'question_meta_box'), KS_GIVEAWAYS_POST_TYPE, 'normal', 'default');
        add_meta_box('ks_contest_images', __('Step 4 &mdash; Design'), array($this, 'design_meta_box'), KS_GIVEAWAYS_POST_TYPE, 'normal', 'default');
    }

    public function info_meta_box($post)
    {
        $shortcodes = $this->get_shortcodes();
        $default_rules = file_get_contents(KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'default_rules.php');
        include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'metabox_info.php';
    }

    public function question_meta_box($post)
    {
        include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'metabox_question.php';
    }

    public function prize_meta_box($post)
    {
        include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'metabox_prize.php';
    }

    public function design_meta_box($post)
    {
        $templates = KS_Giveaways::get_available_templates();

        include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'metabox_design.php';
    }

    public function append_submit_metabox()
    {
        include KS_GIVEAWAYS_PLUGIN_ADMIN_VIEWS_DIR . DIRECTORY_SEPARATOR . 'metabox_stats.php';
    }

    public function set_contest_columns($columns)
    {
        $columns = array();

        $columns['cb'] = '<input type="checkbox" />';
        $columns['title'] = 'Title';
        $columns['num_contestants'] = 'Contestants';
        $columns['num_entries'] = 'Entries';
        $columns['status'] = 'Status';
        $columns['time_remaining'] = 'Remaining';

        return $columns;
    }

    public function display_contest_column($column, $post_id)
    {
        switch($column)
        {
            case 'num_contestants':
                echo sprintf('<a href="%s">%d</a>', admin_url('admin.php?page=ks-giveaways&action=contestants&id=' . $post_id), KS_Contestant_DB::get_total($post_id));
                break;

            case 'num_entries':
                echo KS_Entry_DB::get_contest_total($post_id);
                break;

            case 'status':
                $started = KS_Helper::has_started($post_id);
                $ended = KS_Helper::has_ended($post_id);

                if (!$started) {
                    echo 'Pending';
                } else if ($started && !$ended) {
                    echo 'Running';
                } else if ($ended) {
                    echo 'Ended';
                }
                break;

            case 'time_remaining':
                $started = KS_Helper::has_started($post_id);
                $ended = KS_Helper::has_ended($post_id);

                if (!$started) {
                    $start = KS_Helper::get_date_start($post_id);
                    if ($start) {
                        echo KS_Helper::time_between(time(), $start);
                    } else {
                        echo 'N/A';
                    }
                } else if ($ended) {
                    echo 'N/A';
                } else if ($started && !$ended) {
                    $end = KS_Helper::get_date_end($post_id);
                    if ($end) {
                        echo KS_Helper::time_between(time(), $end);
                    } else {
                        echo 'N/A';
                    }
                }
                break;
        }
    }

    public function set_page_row_actions($actions, $post)
    {
        if ($post->post_type != KS_GIVEAWAYS_POST_TYPE) {
            return $actions;
        }

        unset($actions['inline hide-if-no-js']);
        $actions['view'] = sprintf('<a href="%s">Manage Giveaway</a>', admin_url('admin.php?page=ks-giveaways&action=view&id=' . $post->ID));
        $actions['contestants'] = sprintf('<a href="%s">View Contestants</a>', admin_url('admin.php?page=ks-giveaways&action=contestants&id=' . $post->ID));

        return $actions;
    }

    public function set_page_views($views)
    {
        unset($views['publish']);

        return $views;
    }

    private function has_valid_license()
    {
        return (get_option(KS_GIVEAWAYS_OPTION_LICENSE_STATUS) == 'valid');
    }

    public function current_screen($screen)
    {
        if ($screen && isset($screen->id) && isset($screen->post_type) && !$this->has_valid_license() && in_array($screen->id, array('edit-'.KS_GIVEAWAYS_POST_TYPE, KS_GIVEAWAYS_POST_TYPE)) && $screen->post_type == KS_GIVEAWAYS_POST_TYPE) {
            $url = menu_page_url('ks-giveaways-options', false);
            wp_redirect($url);
            exit;
        }
    }

    public function default_hidden_meta_boxes($hidden, $screen)
    {
        if (isset($screen->id) && $screen->id == KS_GIVEAWAYS_POST_TYPE) {
            $hidden = array_merge($hidden, array('slugdiv'));
        }

        return $hidden;
    }

    public function check_plugin_health()
    {
        $tables = array(
            'ks_giveaways_contestant',
            'ks_giveaways_entry',
            'ks_giveaways_winner'
        );

        $errors = array();

        foreach ($tables as $table) {
            try {
                KS_Debug::check_table_exists($table);
            }
            catch (Exception $e) {
                $errors[] = sprintf('<strong>Fatal:</strong> %s', $e->getMessage());
            }
        }

        if (!KS_Debug::php_version('5.3')) {
            $errors[] = sprintf('<strong>Warning:</strong> PHP version 5.3 or higher is recommended.  Your version is: %s.', PHP_VERSION);
        }

        if (KS_Debug::is_wp_engine()) {
            $errors[] = sprintf('<strong>Warning:</strong> To avoid caching issues on WP Engine please see <a href="%s" target="_blank">this FAQ entry</a>.', 'http://kingsumo.com/faqs/my-site-is-hosted-on-wp-engine-will-giveaways-still-work/');
        }

        $wpseo = get_option('wpseo_permalinks');
        if (is_array($wpseo) && isset($wpseo['cleanpermalinks']) && $wpseo['cleanpermalinks'] == true) {
            $errors[] = sprintf('<strong>Fatal:</strong> WordPress SEO redirect ugly URL\'s setting is enabled.  Please <a href="%s">click here</a> and disable the setting to avoid redirect loops.', admin_url('admin.php?page=wpseo_permalinks'));
        }

        $update_plugins = get_site_transient('update_plugins');
        if (is_object($update_plugins) && is_array($update_plugins->response)) {
            $plugins = $update_plugins->response;
            $data = get_plugin_data(KS_GIVEAWAYS_PLUGIN_FILE);
            if (is_array($data)) {
                $current_version = $data['Version'];
                $plugin_file = plugin_basename(KS_GIVEAWAYS_PLUGIN_FILE);
                if (isset($plugins[$plugin_file])) {
                    $new_version = $plugins[$plugin_file]->new_version;
                    if (version_compare($current_version, $new_version, '<')) {
                        $errors[] = sprintf('<strong>Notice:</strong> A new version of KingSumo Giveaways is available.  <a href="%s">Apply update now</a>.', admin_url('plugins.php?plugin_status=upgrade'));
                    }
                }
            }
        }


        if (count($errors)) {
            $errors = implode("<br />", $errors);
            echo <<<EOF
<div class="error">
  <h3>KingSumo Giveaways found the following issues:</h3>
  <p>{$errors}</p>
</div>
EOF;
        }
    }

    public function admin_notices()
    {
        $url = menu_page_url('ks-giveaways-options', false);

        $screen = get_current_screen();
        if ($screen && isset($screen->id) && isset($screen->post_type) && in_array($screen->id, array('edit-'.KS_GIVEAWAYS_POST_TYPE, KS_GIVEAWAYS_POST_TYPE)) && $screen->post_type == KS_GIVEAWAYS_POST_TYPE) {
            $this->check_plugin_health();
        }

        if (!trim(get_option(KS_GIVEAWAYS_OPTION_LICENSE_KEY))) {
            echo <<<EOF
<div class="error ks-license-error"><p>
<strong>KingSumo Giveaways</strong> does not have a license key yet.
&nbsp;
<a href="{$url}">Click here</a> to enter your license key (Settings &#8594; KingSumo Giveaways)
</p></div>
EOF;
        } else if (get_option(KS_GIVEAWAYS_OPTION_LICENSE_STATUS) != 'valid') {
          echo <<<EOF
<div class="error ks-license-error"><p>
<strong>KingSumo Giveaways</strong> has not been activated yet.
&nbsp;
<a href="{$url}">Click here</a> to activate your license (Settings &#8594; KingSumo Giveaways)
</p></div>
EOF;
        }
    }

}
