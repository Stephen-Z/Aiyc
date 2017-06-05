<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 6/5/17
 * Time: 5:32 PM
 */
class Select_member extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->patch=REST_Controller::MANAGER_PATH;
        $this->load->helper('message_encode');
        $this->load->model('article/Column_model','Column_model',true);
        $this->load->model('article/List_model','List_model',true);
        $this->load->model('article/Content_model','Content_model',true);
        $this->load->model('auth/Signin_model','Signin_model',true);
        $this->nav = 'dispatch_system';
    }

    public function comment_get($articleID){

        $data=array();
        $data['article_id']=$articleID;
        $data['kind']=0;
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'dispatcher_articleList';

        $where=array();
        $where['login_date']=date('Y-m-d');

        $rs=$this->Signin_model->get_many_by($where);
        $data['rs']=$rs;


        $this->load->view($this->template_patch."/dispatch/dispatch_comment",$data);
    }
}