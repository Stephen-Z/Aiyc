<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');

/*
writing by stephen 2017-04-30
controller for 文章正负面
*/

class goodORbad extends REST_Controller{
  public function __construct(){
    parent::__construct();

    $this->template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
    $this->patch=REST_Controller::MANAGER_PATH;

    $this->nav='my_mission';

    $this->load->model('article/List_model','List_model',true);
  }

  public function index_get(){
    $data=array();
    $data['nav'] = $this->nav;
    $data['child_nav'] = 'article_goodorbad';
    $cnrs = array('name' => '文章正负面');
    $data['cnrs']=$cnrs;

    //prepare data
    $orderby_name='id';
    $orderby_value='DESC';

    $skipnum = $this->get('skipnum');
    $length = $this->get('length');
    init_page_params($skipnum, $length);

    $count=$this->List_model->count_all();
    $rs=$this->List_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->get_all();

    $data['rs']=$rs;
    $data['page_total']=$count;

    $this->load->view($this->template_patch.'/article/article_goodorbad',$data);
  }
}
