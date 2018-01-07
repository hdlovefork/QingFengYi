<?php
/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2018-01-04
 * Time: 20:08
 */
include_once APPPATH . 'models/service/Remote_model.php';

class Dataoke_model extends Remote_model
{

    protected $dtk_key;

    /**
     * @var \libraries\daotaoke\DTK_Client
     */
    protected $client;

    public function __construct()
    {
        parent::__construct();
        $this->load->config('app');
        $this->dtk_key = $this->config->item('app_dtk_key');
        $this->client = new \libraries\daotaoke\DTK_Client();
    }

    function get_host_url()
    {
        return '';
    }
}