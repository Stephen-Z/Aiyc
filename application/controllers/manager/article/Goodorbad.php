<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');

/*
writing by stephen 2017-04-30
controller for 文章正负面
*/

class Goodorbad extends REST_Controller{
  public function __construct(){
    parent::__construct();

    $this->template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
    $this->path=REST_Controller::MANAGER_PATH;

    $this->nav='my_mission';

    $this->load->model('article/List_model','List_model',true);
  }

  public function index_get(){
    $data=array();
    $data['nav'] = $this->nav;
    $data['child_nav'] = 'article_goodORbad';
    $cnrs = array('name' => '文章正负面');
    $data['cnrs']=$cnrs;

    $data['token_name'] = $this->security->get_csrf_token_name();
    $data['hash'] = $this->security->get_csrf_hash();

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

  //stephen 2017-05-06 positive update;
    public function update_post(){
        $articleId = $this->input->post('articleid');
        $newStatus = $this->input->post('status');

        $data=array();
        $data['positive']=$newStatus;

        if($this->List_model->update($articleId,$data)){
            echo '1';
        }else{
            echo '0';
        }
    }

}