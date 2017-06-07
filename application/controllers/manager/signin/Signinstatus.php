<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 5/2/17
 * Time: 11:01 PM
 * Controller for sign_in_status
 */
class Signinstatus extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template_path=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->path=REST_Controller::MANAGER_PATH;
        $this->nav='account';
        $this->load->model('auth/Signin_model','Signin_model',true);
    }

    public function index_get(){
        $data=array();

        $data['nav']=$this->nav;
        $data['child_nav']='sign_in';

        $cnrs=array('name' => '签到');
        $data['cnrs']=$cnrs;

        $cuswhere=array();
        $cuswhere['login_date']=date('Y-m-d');

        $orderby_name='login_date';
        $orderby_value='DESC';

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        $count=$this->Signin_model->count_all();
        $rs=$this->Signin_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->left_join_admin(date('Y-m-d'));

        $data['rs']=$rs;
        $data['page_total']=$count;



        $this->load->view($this->template_path.'/signIn/sign_in',$data);
    }
}