<?php

if(!class_exists('KS_Giveaways_Vendor_GetResponse'))
{
    require_once(dirname(__FILE__)."/vendor/getresponse_api/GetResponseAPI.class.php");
}

class KS_Giveaways_GetResponse
{
    /**
     * Instance of this class.
     */
    protected static $instance;

    /**
     * Instance of the GetResponse API connector
     *
     * @var $getresponse KS_Giveaways_Vendor_GetResponse
     */
    protected $getresponse;

    const E_CONTACT_ALREADY_ADDED = "Contact already added to target campaign";
    const E_CONTACT_ALREADY_QUEUED = "Contact already queued for target campaign";

    private function __construct()
    {
        $this->key = get_option(KS_GIVEAWAYS_OPTION_GETRESPONSE_KEY);

        $this->getresponse = new KS_Giveaways_Vendor_GetResponse($this->key);

        if(!$this->ping())
        {
            $this->getresponse = NULL;
        }
    }

    /**
     * Returns an instance of this class.
     *
     * @return  object    A single instance of this class.
     */
    public static function get_instance()
    {
        if(null == self::$instance)
        {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function get_campaigns()
    {
        if($this->getresponse === NULL)
        {
            return false;
        }

        $lists = array();
        $ret = $this->getresponse->getCampaigns();

        foreach($ret as $campaignId => $campaign)
        {
            $lists[$campaignId] = $campaign->name;
        }

        return $lists;
    }

    /**
     * Add subscriber to a GetResponse campaign.
     *
     * @param $campaign_id
     * @param $email
     * @return bool
     */
    public function add_subscriber($campaign_id, $email)
    {
        if($this->getresponse === NULL)
        {
            return false;
        }

        // Note: This does not need error supression because the original code has been edited. @see GetResponse::execute2
        $response = $this->getresponse->addContact($campaign_id, $email); // TODO: Ask what name?

        if(isset($response->result))
        {
            return true;
        }

        // On GetResponse Error
        if(isset($response->error))
        {
            if($response->error->message === self::E_CONTACT_ALREADY_ADDED or
                $response->error->message === self::E_CONTACT_ALREADY_QUEUED) // TODO: Ask: True or false on already queued?
            {
                return true; // Not a critical error, return true.
            }
            else
            {
                return false;
            }
        }

        return false;
    }

    public function ping()
    {
        // Method will throw an error on failure. Suppress that.
        $result = @$this->getresponse->ping();

        if($result === "pong")
        {
            return true;
        }
        else
        {
            // Note: $result will be NULL
            return false;
        }
    }

    public static function disconnect()
    {
        delete_option(KS_GIVEAWAYS_OPTION_GETRESPONSE_KEY);
    }

    public static function is_valid()
    {
        if(get_option(KS_GIVEAWAYS_OPTION_GETRESPONSE_KEY))
        {
            $cls = self::get_instance();

            return $cls->ping();
        }

        return false;
    }
}