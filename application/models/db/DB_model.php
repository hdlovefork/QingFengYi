<?php

/**
 * Created by PhpStorm.
 * User: hdlovefork
 * Date: 2017-12-11
 * Time: 15:25
 */
class DB_model extends CI_Model
{
   const TABLE_APP = 'app';
   const TABLE_USER = 'user';
   const TABLE_BANNER='banner';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
}