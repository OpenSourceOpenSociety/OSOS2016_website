<?php

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-contestant-db.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-entry-db.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-winner-db.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-helper.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-aweber.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-mailchimp.php';
require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'class-sync-getresponse.php';

require_once KS_GIVEAWAYS_PLUGIN_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'wordpress-common' . DIRECTORY_SEPARATOR . 'class-ks-http.php';

/**
 * @package     KS_Giveaways
 */
class KS_Giveaways
{
    /**
     * Version used for stylesheet and Javascript assets.
     */
    const VERSION = KS_GIVEAWAYS_EDD_VERSION;

    protected $plugin_slug = 'ks-giveaways';

    /**
     * Instance of this class.
     */
    protected static $instance = null;

    public static $default_template = '%wp_content_dir%/plugins/ks-giveaways/templates/responsive3/index.php';

    private function __construct()
    {
        add_action('init', array($this, 'init'), 1);

        add_action('template_redirect', array($this, 'template_redirect'));
        add_action('ks_giveaways_add_contestant', array($this, 'new_contestant_added'));
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

    public static function _activate($network_wide)
    {
        if (function_exists('is_multisite') && is_multisite() && $network_wide) {
          self::_network_call(array(__CLASS__, 'activate'), $network_wide);
        } else {
          self::activate();
        }
    }

    public static function _deactivate($network_wide)
    {
        if (function_exists('is_multisite') && is_multisite() && $network_wide) {
          self::_network_call(array(__CLASS__, 'deactivate'), $network_wide);
        } else {
          self::deactivate();
        }
    }

    protected static function _network_call($func)
    {
        $args = func_get_args();
        $func = array_shift($args);

        global $wpdb;

        $old_blog = $wpdb->blogid;

        $blogids = $wpdb->get_col("SELECT blog_id FROM {$wpdb->blogs}");
        foreach ($blogids as $blog_id) {
            switch_to_blog($blog_id);
            call_user_func_array($func, $args);
        }
        switch_to_blog($old_blog);
    }

    public static function activate()
    {
        self::register_post_types();
        flush_rewrite_rules();

        self::check_database_tables();
        self::check_default_options();
    }

    public static function deactivate()
    {
    }

    public function get_plugin_slug()
    {
        return $this->plugin_slug;
    }

    /**
     * Load the plugin text domain for translation.
     */
    public function load_text_domain()
    {
        $domain = $this->plugin_slug;
        $locale = apply_filters('plugin_locale', get_locale(), $domain);

        load_textdomain($domain, trailingslashit(WP_LANG_DIR) . $domain . '/' . $domain . '-' . $locale . '.mo');
        load_plugin_textdomain($domain, false, basename(plugin_dir_path(dirname(__FILE__))) . '/languages/');
    }

    /**
     * Register and enqueue the public-facing stylesheet.
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_slug . '-plugin-styles', plugins_url('assets/css/public.css', __FILE__), array(), self::VERSION);
    }

    /**
     * Register and enqueue public-facing Javascript files.
     */
    public function enqueue_scripts()
    {
        wp_enqueue_scripts($this->plugin_slug . '-plugin-script', plugins_url('assets/js/public.js', __FILE), array('jquery'), self::VERSION);
    }

    public function init()
    {
        self::check_database_tables();
        self::register_post_types();
    }

    public static function check_default_options()
    {
        // add default options to DB
        add_option(KS_GIVEAWAYS_OPTION_EMAIL_FROM_ADDRESS, sprintf('%s <%s>', get_bloginfo('name'), get_bloginfo('admin_email')));
        add_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUBJECT, 'Confirm your entry for "[name]"');
        add_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_BODY, <<<EOF
<p>Thanks for entering "[name]".</p>
<p>Visit <a href="[confirm_url]">[confirm_url]</a> to confirm your entry.</p>
<p>Don't forget to share your lucky URL as much as possible to increase your chances of winning.  You will receive [entries_per_friend] extra entries for every person who enters via your lucky URL.</p>
<p>Your Lucky URL: <a href="[lucky_url]">[lucky_url]</a></p>
<p>
Regards,<br>
[site_name]</p>
EOF
        );

        add_option(KS_GIVEAWAYS_OPTION_WINNER_EMAIL_SUBJECT, 'Congratulations! You won the "[name]" giveaway');
        add_option(KS_GIVEAWAYS_OPTION_WINNER_EMAIL_BODY, <<<EOF
<p>Thanks for entering "[name]".</p>
<p>We are just letting you know that you have won, how awesome is that?!</p>
<p>Stay tuned and we will contact you soon about collecting your [prize_name].</p>
<p>
Regards,<br>
[site_name]</p>
EOF
        );
    }

    public static function check_database_tables()
    {
        KS_Contestant_DB::check_database_table(self::VERSION);
        KS_Entry_DB::check_database_table(self::VERSION);
        KS_Winner_DB::check_database_table(self::VERSION);
    }

    public static function register_post_types()
    {
        $labels = array(
            'name' => 'Giveaways',
            'singular_name' => 'Giveaway',
            'add_new' => 'Add New',
            'add_new_item' => 'Add New Giveaway',
            'edit_item' => 'Edit Giveaway',
            'new_item' => 'New Giveaway',
            'view_item' => 'View Giveaway',
            'search_items' => 'Search Giveaways',
            'not_found' => 'No giveaways found',
            'not_found_in_trash' => 'No giveaways found in trash',
            'menu_name' => 'KingSumo Giveaways',
            'all_items' => 'All Giveaways'
        );

        $args = array(
            'labels' => $labels,
            'public' => true,
            'public_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'giveaways', 'with_front' => true),
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'supports' => array('title'),
            'menu_icon' => plugin_dir_url(__FILE__) . '../admin/assets/images/ks-giveaways-icon-wp-solid-detailed.png'
        );

        register_post_type(
            KS_GIVEAWAYS_POST_TYPE,
            $args
        );
    }

    public function verify_captcha($response)
    {
        $args = array(
            'secret' => get_option(KS_GIVEAWAYS_OPTION_CAPTCHA_SECRET_KEY),
            'response' => $response,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        );

        $qs = http_build_query($args);
        $ret = KS_Http::get('https://www.google.com/recaptcha/api/siteverify?' . $qs);
        if ($ret) {
          $answers = json_decode($ret, true);
          if (is_array($answers) && trim($answers['success'])) {
              return true;
          }
        }

        return false;
    }

    public function template_redirect()
    {
        if (!is_single()) {
            return;
        }

        $post = get_post();
        if (get_post_type($post->ID) != KS_GIVEAWAYS_POST_TYPE) {
            return;
        }

        // Tell CloudFlare not to cache
        header('Cache-Control: private, no-cache, no-store, max-age=0, must-revalidate, proxy-revalidate'); // HTTP 1.1.
        header('Pragma: no-cache'); // HTTP 1.0.
        header('Expires: 0'); // Proxies.

        define('DONOTCACHEPAGE', true);
        define('DONOTCACHEDB', true);
        define('DONOTCACHCEOBJECT', true);
        define('DONOTMINIFY', true);

        if (!is_preview() && $_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['giveaways_nonce']) && wp_verify_nonce($_POST['giveaways_nonce'], 'ks_giveaways_form') && (!get_option(KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY) || (get_option(KS_GIVEAWAYS_OPTION_CAPTCHA_SITE_KEY) && $this->verify_captcha($_POST['g-recaptcha-response']))) ) {
            // POSTing to giveaway
            if (KS_Helper::is_running($post)) {
                $email = isset($_POST['giveaways_email']) ? sanitize_email($_POST['giveaways_email']) : null;
                $answer = isset($_POST['giveaways_answer']) ? $_POST['giveaways_answer'] : null;
                $passed_sig = isset($_POST['giveaways_sig']) ? $_POST['giveaways_sig'] : null;

                $right_answer = KS_Helper::get_right_answer($post);
                $calculated_sig = md5($right_answer . '|' . $email);

                $referral_id = isset($_REQUEST['lucky']) ? (int) $_REQUEST['lucky'] : null;
                if (!$referral_id) $referral_id = null;

                if ($email && $answer && $calculated_sig == $passed_sig) {
                    $pi = parse_url(get_permalink());
                    $base = dirname(rtrim($pi['path'], '/')) . '/';
                    setcookie(KS_GIVEAWAYS_COOKIE_EMAIL_ADDRESS, $email, time() + YEAR_IN_SECONDS, $base);

                    $contestant = $this->add_contestant_entry($post->ID, $email, $referral_id);
                    if ($contestant) {
                        // redirect to lucky URL
                        $url = KS_Helper::get_lucky_url($post, $contestant);
                        wp_redirect($url);
                        exit;
                    }
                }
            }
        } else if (!is_preview() && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['confirm']) && isset($_GET['key'])) {
            // confirming email address
            $id = $_GET['confirm'];
            $key = $_GET['key'];

            $contestant = KS_Contestant_DB::get($id);
            if ($contestant) {
                if ($contestant->confirm_key == $key) {
                    KS_Contestant_DB::update_status($contestant->ID, 'confirmed');

                    $this->set_post_cookie(KS_GIVEAWAYS_COOKIE_CONTESTANT.$post->ID, $contestant->ID);
                }

                // redirect to lucky URL
                $url = KS_Helper::get_lucky_url($post, $contestant);
                wp_redirect($url);
                exit;
            }

            wp_redirect(get_permalink());
            exit;
        } else if (!is_preview() && $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_COOKIE[KS_GIVEAWAYS_COOKIE_CONTESTANT.$post->ID])) {
            // returning after a cookie was set
            $id = $_COOKIE[KS_GIVEAWAYS_COOKIE_CONTESTANT.$post->ID];

            $contestant = KS_Contestant_DB::get($id);
            if ($contestant) {
                $GLOBALS['ks_giveaways_contestant'] = $contestant;

                if (KS_Helper::is_running($post)) {
                    // handle referral - credit logged in user with referral if needed
                    $referral_id = isset($_REQUEST['lucky']) ? (int) $_REQUEST['lucky'] : null;
                    if (!$referral_id) $referral_id = null;
                    $referral = $referral_id ? KS_Contestant_DB::get($referral_id, $post->ID) : null;
                    if ($referral && $referral->ID != $contestant->ID) {
                        $this->add_referral_entry($post->ID, $contestant, $referral);
                    }
                }

                // redirect to lucky URL if not on it
                if (!isset($_GET['lucky']) || $_GET['lucky'] != $contestant->ID) {
                    $url = KS_Helper::get_lucky_url($post, $contestant);
                    wp_redirect($url);
                    exit;
                }
            }
        }

        $template = $this->get_template(null);
        include $template;
        exit;
    }

    public function new_contestant_added($email_address)
    {
        // aweber
        $list_id = get_option(KS_GIVEAWAYS_OPTION_AWEBER_LIST_ID);
        if ($list_id && KS_Giveaways_Aweber::is_valid()) {
            $cls = KS_Giveaways_Aweber::get_instance();
            $cls->add_subscriber($list_id, $email_address);
        }

        // mailchimp
        $list_id = get_option(KS_GIVEAWAYS_OPTION_MAILCHIMP_LIST_ID);
        if ($list_id && KS_Giveaways_Mailchimp::is_valid()) {
            $cls = KS_Giveaways_Mailchimp::get_instance();
            $cls->add_subscriber($list_id, $email_address);
        }

        // GetResponse
        $campaignId = get_option(KS_GIVEAWAYS_OPTION_GETRESPONSE_CAMPAIGN_ID);
        if($campaignId and KS_Giveaways_GetResponse::is_valid()) {
            $cls = KS_Giveaways_GetResponse::get_instance();
            $cls->add_subscriber($campaignId, $email_address);
        }
    }

    public static function get_available_templates()
    {
        $file_headers = array(
            'TemplateName' => 'Template Name',
            'TemplateAuthor' => 'Template Author'
        );
        $path = KS_GIVEAWAYS_PLUGIN_TEMPLATES_DIR;
        $files = glob(trailingslashit($path) . '**' . DIRECTORY_SEPARATOR . '*.php');

        // normal files to correct symlinks
        foreach ($files as &$file) {
            if (strpos($file, WP_CONTENT_DIR) !== 0) {
                $file = trailingslashit(WP_PLUGIN_DIR) . plugin_basename($file);

                if (!file_exists($file) && defined('WPMU_PLUGIN_DIR')) {
                    $file = trailingslashit(WPMU_PLUGIN_DIR) . plugin_basename($file);
                }
            }
        }

        $path = trailingslashit(trailingslashit(WP_CONTENT_DIR) . 'ks-giveaways-themes');
        $custom_files = glob($path . '**' . DIRECTORY_SEPARATOR . '*.php');
        if ($custom_files) {
            $files = array_merge($files, $custom_files);
        }
        $search = array(
            untrailingslashit(WP_CONTENT_DIR)
        );
        $replace = array(
            '%wp_content_dir%'
        );

        $templateNames = array();
        $templates = array();
        foreach ($files as $file) {
            $headers = get_file_data($file, $file_headers);
            if ($headers && is_array($headers) && isset($headers['TemplateName']) && !empty($headers['TemplateName'])) {
                $file = str_replace($search, $replace, $file);

                // override previous theme with same name
                $templateName = $headers['TemplateName'];
                if (isset($templateNames[$templateName])) {
                    $headers['IsOverride'] = true;
                }

                $templates[$file] = $headers;
                $templateNames[$headers['TemplateName']] = $file;
            }
        }

        return $templates;
    }

    public function add_contestant_entry($contest_id, $email, $referral_id = null)
    {
        // verify contestant is first-time
        $contestant = KS_Contestant_DB::get_existing($contest_id, $email);
        $referral = $referral_id ? KS_Contestant_DB::get($referral_id, $contest_id) : null;

        if (!$contestant) {
            // add new contestant
            $contestant = KS_Contestant_DB::add($contest_id, $email);

            // add contestant entry
            if ($contestant) {
                KS_Entry_DB::add($contestant->ID, $referral ? $referral->ID : null);

                if (!get_option(KS_GIVEAWAYS_OPTION_ENTRY_EMAIL_SUPPRESS)) {
                    KS_Helper::send_confirm_email($contestant);
                }
            }
        }

        // handle referral
        if ($referral && $referral->ID != $contestant->ID) {
            $this->add_referral_entry($contest_id, $contestant, $referral);
        }

        $this->set_post_cookie(KS_GIVEAWAYS_COOKIE_CONTESTANT.$contest_id, $contestant->ID);

        $GLOBALS['ks_giveaways_contestant'] = $contestant;

        return $contestant;
    }

    public function add_referral_entry($contest_id, $contestant, $referral)
    {
        $has_referral = KS_Entry_DB::has_referral($referral->ID, $contestant->ID);
        $entries_per_friend = KS_Helper::get_entries_per_friend($contest_id);
        if (!$has_referral) {
            for ($i = 0; $i < $entries_per_friend; $i++) {
                // add 3 more entries to the referer with his referral set as contestant
                KS_Entry_DB::add($referral->ID, $contestant->ID);
            }
        }
    }

    public function set_post_cookie($name, $value, $time = null)
    {
        if ($time === null) {
            $time = time() + YEAR_IN_SECONDS;
        }

        $pi = parse_url(get_permalink());
        setcookie($name, $value, $time, $pi['path']);

    }

    public function get_template($template)
    {
        if (is_singular(KS_GIVEAWAYS_POST_TYPE)) {
            require_once KS_GIVEAWAYS_PLUGIN_PUBLIC_INCLUDES_DIR . DIRECTORY_SEPARATOR . 'template_functions.php';

            global $post;

            $template_file = get_post_meta($post->ID, '_template_file', true);
            if (!$template_file) {
                $template_file = KS_Giveaways::$default_template;
            }

            $search = array(
                '%wp_content_dir%'
            );
            $replace = array(
                untrailingslashit(WP_CONTENT_DIR)
            );

            $template_file = str_replace($search, $replace, $template_file);

            return $template_file;
        }

        return $template;
    }
}
