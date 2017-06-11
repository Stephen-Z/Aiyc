<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');
/**
 * Created by PhpStorm.
 * User: lzm
 * Date: 5/3/17
 * Time: 4:37 PM
 */
class Dbtest extends REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model('article/Onlinecomment_model','Onlinecomment_model',true);
    }

    public function index_get(){
        var_dump($this->Onlinecomment_model->join_reply_like());
    }

}