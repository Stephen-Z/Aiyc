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
        $this->load->model('article/List_model','List_model',true);
    }

    public function index_get(){
        //$articleId = $this->input->post('articleid');
        //$newStatus = $this->input->post('status');
        $articleId=755;
        $newStatus=1;

        $data=array();
        $data['status']=$newStatus;

        if($this->List_model->update($articleId,$data)){
            echo '1';
        }else{
            echo '0';
        }
    }

}