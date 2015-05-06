<?php

if (!class_exists('AWeberAPI')) {
    require_once dirname(__FILE__) . '/vendor/aweber_api/aweber_api.php';
}

class KS_Giveaways_Aweber
{
    /**
     * Instance of this class.
     */
    protected static $instance = null;

    protected $aweber = null;

    private function __construct()
    {
        $this->aweber = new AWeberAPI(get_option(KS_GIVEAWAYS_OPTION_AWEBER_CONSUMER_KEY), get_option(KS_GIVEAWAYS_OPTION_AWEBER_CONSUMER_SECRET));

        $this->accessKey = get_option(KS_GIVEAWAYS_OPTION_AWEBER_ACCESS_KEY);
        $this->accessSecret = get_option(KS_GIVEAWAYS_OPTION_AWEBER_ACCESS_SECRET);
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

    public function get_lists()
    {
        try {
            $account = $this->aweber->getAccount($this->accessKey, $this->accessSecret);
            $lists = array();
            foreach ($account->lists as $offset => $list) {
                $lists[sprintf('%d|%d', $account->id, $list->id)] = $list->name;
            }

            return $lists;
        }
        catch(Exception $e) {
        }

        return false;
    }

    public function add_subscriber($list_id, $email)
    {
        if (is_array($list_id)) {
            $account_id = $list_id[0];
            $list_id = $list_id[1];
        } else if (is_string($list_id)) {
            list($account_id, $list_id) = explode('|', $list_id);
        }

        try {
            $account = $this->get_account();
            if (!$account)
                throw new Exception('Unable to get Aweber account');

            $list = $account->loadFromUrl("/accounts/{$account_id}/lists/{$list_id}");
            if (!$list)
                throw new Exception('Unable to get Aweber list');

            $params = array(
                'email' => $email
            );

            $subscribers = $list->subscribers;
            $new_subscriber = $subscribers->create($params);
            if (!$new_subscriber)
                throw new Exception('Error adding Aweber subscriber');

            return $new_subscriber;
        }

        catch(Exception $e) {

        }

        return false;
    }

    public function get_account()
    {
        try {
            return $this->aweber->getAccount($this->accessKey, $this->accessSecret);
        }
        catch(Exception $e) {
        }

        return false;
    }

    public static function auth_from_key($key)
    {
        return AWeberAPI::getDataFromAweberID($key);
    }

    public static function disconnect()
    {
        delete_option(KS_GIVEAWAYS_OPTION_AWEBER_CONSUMER_KEY);
        delete_option(KS_GIVEAWAYS_OPTION_AWEBER_CONSUMER_SECRET);
        delete_option(KS_GIVEAWAYS_OPTION_AWEBER_ACCESS_KEY);
        delete_option(KS_GIVEAWAYS_OPTION_AWEBER_ACCESS_SECRET);
    }

    public static function is_valid()
    {
        if (get_option(KS_GIVEAWAYS_OPTION_AWEBER_ACCESS_KEY) && get_option(KS_GIVEAWAYS_OPTION_AWEBER_ACCESS_SECRET)) {
            $cls = self::get_instance();

            $account = $cls->get_account();

            return ($account && $account->id > 0);
        }

        return false;
    }
}