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
        $this->load->model('article/List_model','List_model',true);
        $this->load->model('auth/Signin_model','Signin_model',true);
        $this->load->model('dispatch/Dispatch_model','Dispatch_model',true);
        $this->load->model('dispatch/Replydispatch_model','Replydispatch_model',true);
        $this->nav = 'dispatch_system';
    }

    public function comment_get($articleID){

        $data=array();
        $data['article_id']=$articleID;
        $data['kind']=0;
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'dispatcher_articleList';
        $cnrs=array('name' => '评论文章');
        $data['cnrs']=$cnrs;

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $where=array();
        $where['login_date']=date('Y-m-d');
        $where['is_admin']=0;
        $where['logout']=0;

        $rs=$this->Signin_model->get_many_by($where);
//        $cuswhere=array();
//        $cuswhere['operation']=0;
//        $cuswhere['article_id']=$articleID;
//        $cuswhere['deleted']=0;
//        $i=0;
//        foreach($rs as $rs_row ){
//            $cuswhere['member_id']=$rs_row['user_id'];
//            if($this->Dispatch_model->count_by($cuswhere)!=0){
//                unset($rs[$i]);
//            }
//            $i++;
//        }

        $data['rs']=$rs;


        $this->load->view($this->template_patch."/dispatch/dispatch_comment",$data);
    }

    public function selected_post(){
        $adminID=$_SESSION['admin']['id'];
        $memberID=$this->input->post('member_id');
        $articleID=$this->input->post('article_id');
        $operation=$this->input->post('operation');

        $data=array();
        $data['admin_id']=$adminID;
        $data['member_id']=$memberID;
        $data['article_id']=$articleID;
        $data['operation']=$operation;

        if($this->Dispatch_model->insert($data)){
            echo 1;
        }
        else{
            echo 2;
        }
    }

    public function positive_get($articleID){
        $data=array();
        $data['article_id']=$articleID;
        $data['kind']=0;
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'dispatcher_articleList';
        $cnrs=array('name' => '评价文章');
        $data['cnrs']=$cnrs;

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $where=array();
        $where['login_date']=date('Y-m-d');
        $where['is_admin']=0;
        $where['logout']=0;

        $rs=$this->Signin_model->get_many_by($where);
//        $cuswhere=array();
//        $cuswhere['operation']=1;
//        $cuswhere['article_id']=$articleID;
//        $cuswhere['deleted']=0;
//        $i=0;
//        foreach($rs as $rs_row ){
//            $cuswhere['member_id']=$rs_row['user_id'];
//            if($this->Dispatch_model->count_by($cuswhere)!=0){
//                unset($rs[$i]);
//            }
//            $i++;
//        }
        $data['rs']=$rs;
        $this->load->view($this->template_patch."/dispatch/dispatch_positive",$data);
    }

    public function replypositive_get($articleID){
        $data=array();
        $data['reply_id']=$articleID;
        $data['kind']=0;
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'dispatcher_articleList';
        $cnrs=array('name' => '评价文章');
        $data['cnrs']=$cnrs;

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $where=array();
        $where['login_date']=date('Y-m-d');
        $where['is_admin']=0;
        $where['logout']=0;

        $rs=$this->Signin_model->get_many_by($where);
//        $cuswhere=array();
//        $cuswhere['operation']=1;
//        $cuswhere['reply_id']=$articleID;
//        $cuswhere['deleted']=0;
//        $i=0;
//        foreach($rs as $rs_row ){
//            $cuswhere['member_id']=$rs_row['user_id'];
//            if($this->Replydispatch_model->count_by($cuswhere)!=0){
//                unset($rs[$i]);
//            }
//            $i++;
//        }
        $data['rs']=$rs;
        $this->load->view($this->template_patch."/dispatch/selectmember_replypositive",$data);
    }


    public function replydispatch_get($replyid){
        $data=array();
        $data['reply_id']=$replyid;
        $data['kind']=0;
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'dispatcher_replylist';
        $cnrs=array('name' => '评论回复');
        $data['cnrs']=$cnrs;

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $where=array();
        $where['login_date']=date('Y-m-d');
        $where['is_admin']=0;
        $where['logout']=0;

        $rs=$this->Signin_model->get_many_by($where);
//        $cuswhere=array();
//        $cuswhere['operation']=0;
//        $cuswhere['reply_id']=$replyid;
//        $cuswhere['deleted']=0;
//        $i=0;
//        foreach($rs as $rs_row ){
//            $cuswhere['member_id']=$rs_row['user_id'];
//            if($this->Replydispatch_model->count_by($cuswhere)!=0){
//                unset($rs[$i]);
//            }
//            $i++;
//        }

        $data['rs']=$rs;

        $this->load->view($this->template_patch."/dispatch/selectmember_replydispatch",$data);
    }



    public function replycommentselected_post(){
        $adminID=$_SESSION['admin']['id'];
        $memberID=$this->input->post('member_id');
        $articleID=$this->input->post('reply_id');
        $operation=$this->input->post('operation');

        $data=array();
        $data['admin_id']=$adminID;
        $data['member_id']=$memberID;
        $data['reply_id']=$articleID;
        $data['operation']=$operation;

        if($this->Replydispatch_model->insert($data)){
            echo 1;
        }
        else{
            echo 2;
        }
    }
}