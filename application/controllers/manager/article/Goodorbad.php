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
    $this->load->model('dispatch/Dispatch_model','Dispatch_model',true);
    $this->load->model('dispatch/Replydispatch_model','Replydispatch_model',true);
      $this->load->model('article/Onlinecomment_model','Onlinecomment_model',true);
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
    $orderby_name='Dcreated';
    $orderby_value='DESC';

    $skipnum = $this->get('skipnum');
    $length = $this->get('length');
    init_page_params($skipnum, $length);

    $where=array();
    $where['member_id']=$_SESSION['admin']['id'];
    $where['operation']=1;

    $tmprs=$this->Dispatch_model->get_many_by($where);
    $count=count($tmprs);

    $rs=$this->Dispatch_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->join_article($where['member_id'],1);

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
            $update_data=array();
            $update_data['member_commit']=time();
            $this->Dispatch_model->update_by(array('member_id' => $_SESSION['admin']['id']),$update_data);
            echo '1';
        }else{
            echo '0';
        }
    }

    public function replyupdate_post(){
        $articleId = $this->input->post('articleid');
        $newStatus = $this->input->post('status');

        $data=array();
        $data['positive']=$newStatus;

        if($this->Onlinecomment_model->update($articleId,$data)){
            $update_data=array();
            $update_data['member_commit']=time();
            $this->Replydispatch_model->update_by(array('member_id' => $_SESSION['admin']['id']),$update_data);
            echo '1';
        }else{
            echo '0';
        }
    }


    public function reply_get(){
        $data=array();
        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $data['nav']=$this->nav;
        $data['child_nav']='reply_positive';

        $cnrs=array('name' => '回复评价');
        $data['cnrs']=$cnrs;

        //prepare data
        $orderby_name='reply_dispatch.created';
        $orderby_value='DESC';

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        $where=array();
        $where['member_id']=$_SESSION['admin']['id'];
        $where['operation']=1;

        $tmprs=$this->Replydispatch_model->get_many_by($where);
        $count=count($tmprs);

        $rs=$this->Replydispatch_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->left_join_comment($where['member_id'],1);

        $data['rs']=$rs;
        $data['page_total']=$count;

        $this->load->view($this->template_patch.'/article/article_replypositive',$data);
    }

}
