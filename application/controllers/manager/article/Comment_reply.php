<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 30/4/2017
 * Time: 11:45 PM
 */
class Comment_reply extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->template_path=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->path=REST_Controller::MANAGER_PATH;

        $this->nav='my_mission';
    }

    public function index_get(){
        $data=array();

        $data['nav']=$this->nav;
        $data['child_nav']='comment_reply';

        $cnrs=array('name' => '评论回复');
        $data['cnrs']=$cnrs;

        $this->load->view($this->template_path.'/article/comment_positive',$data);
    }
}