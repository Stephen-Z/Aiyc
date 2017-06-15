<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 6/7/17
 * Time: 10:45 AM
 */
class Dispatched extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->patch=REST_Controller::MANAGER_PATH;
        $this->load->helper('message_encode');
        $this->load->model('dispatch/Dispatch_model','Dispatch_model',true);
        $this->load->model('auth/Auth_model','Auth_model',true);
        $this->load->model('dispatch/Replydispatch_model','Replydispatch_model',true);
        $this->load->model('article/List_model','List_model',true);
        $this->load->model('article/Onlinecomment_model','Onlinecomment_model',true);
        $this->nav = 'dispatch_system';
    }

    public function index_get(){
        $data=array();
        $data['nav']=$this->nav;
        $data['child_nav']='dispatched_list';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $where=array();
        $where['admin_id']=$_SESSION['admin']['id'];
        $rs=$this->Dispatch_model->get_many_by($where);

        $newrs=array();
        foreach ($rs as $rs_row){
            $cuswhere=array();
            $cuswhere['id']=$rs_row['member_id'];
            $tmp=$this->Auth_model->get_by($cuswhere);
            $rs_row['name']=$tmp['name'];
            $cuswhere=array();
            $cuswhere['id']=$rs_row['article_id'];
            $article_tmp=$this->List_model->get_by($cuswhere);
            $rs_row['article_title']=$article_tmp['title'];
            array_push($newrs,$rs_row);
        }

        $data['rs']=$newrs;

        $this->load->view($this->template_patch.'/dispatch/dispatched_list',$data);
    }

    public function deleted_post(){
        $member_id=$this->input->post('member_id');
        $article_id=$this->input->post('article_id');
        $operation=$this->input->post('operation');

        $where=array();
        $where['member_id']=$member_id;
        $where['article_id']=$article_id;
        $where['admin_id']=$_SESSION['admin']['id'];
        $where['operation']=$operation;

        if($this->Dispatch_model->delete_by($where)){
            echo 1;
        }
        else{
            echo 0;
        }
    }

    public function reply_get(){
        $data=array();
        $data['nav']=$this->nav;
        $data['child_nav']='dispatched_replylist';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $where=array();
        $where['admin_id']=$_SESSION['admin']['id'];
        $rs=$this->Replydispatch_model->get_many_by($where);

        $newrs=array();
        foreach ($rs as $rs_row){
            $cuswhere=array();
            $cuswhere['id']=$rs_row['member_id'];
            $tmp=$this->Auth_model->get_by($cuswhere);
            $rs_row['name']=$tmp['name'];
            $cuswhere=array();
            $cuswhere['order_id']=$rs_row['reply_id'];
            $comment_tmp=$this->Onlinecomment_model->get_by($cuswhere);
            $rs_row['online_comment']=$comment_tmp['comment_content'];
            $cuswhere=array();
            $cuswhere['id']=$comment_tmp['article_id'];
            $article_tmp=$this->List_model->get_by($cuswhere);
            $rs_row['article_title']=$article_tmp['title'];
            array_push($newrs,$rs_row);
        }
        $data['rs']=$newrs;

        $this->load->view($this->template_patch.'/dispatch/replydispatched_list',$data);
    }

    public function replydelete_post(){
        $member_id=$this->input->post('member_id');
        $article_id=$this->input->post('article_id');
        $operation=$this->input->post('operation');

        $where=array();
        $where['member_id']=$member_id;
        $where['reply_id']=$article_id;
        $where['admin_id']=$_SESSION['admin']['id'];
        $where['operation']=$operation;

        if($this->Replydispatch_model->delete_by($where)){
            echo 1;
        }
        else{
            echo 0;
        }
    }
}