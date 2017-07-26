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
        $this->load->model('article/Comment_model','Comment_model',true);
        $this->load->model('article/Onlinecomment_model','Onlinecomment_model',true);
        $this->nav = 'dispatch_system';
    }

    public function index_get(){
        $data=array();
        $data['nav']=$this->nav;
        $data['child_nav']='dispatched_list';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        $where=array();
        $where['admin_id']=$_SESSION['admin']['id'];
        $rs=$this->Dispatch_model->limit($length, $skipnum)->order_by('id','desc')->get_many_by($where);



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

        $data['page_total']=count($newrs);

        $this->load->view($this->template_patch.'/dispatch/dispatched_list',$data);
    }

    public function deleted_post(){
//        $member_id=$this->input->post('member_id');
        $article_id=$this->input->post('article_id');
//        $operation=$this->input->post('operation');
        $task_id=$this->input->post('task_id');

//        $article_tmp=$this->List_model->get_by(array('id'=>$article_id));
//        $replycount=$article_tmp['reply'];
//        $replycount-=1;

        $where=array();
        $where['id']=$task_id;
//        $where['article_id']=$article_id;
//        $where['admin_id']=$_SESSION['admin']['id'];
//        $where['operation']=$operation;

        if($this->Dispatch_model->delete_by($where)){
            $replycount=$this->Dispatch_model->count_by(array('article_id'=>$article_id,'task_done'=>3,'deleted'=>0,'operation'=>0));
            $this->List_model->update_by(array('id'=>$article_id),array('reply'=>$replycount));
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
        $task_id=$this->input->post("taskid");

        $where=array();
        $where['id']=$task_id;

        if($this->Replydispatch_model->delete_by($where)){
            echo 1;
        }
        else{
            echo 0;
        }
    }

    public function secondconfirmarticle_get(){
        $before_time=strtotime('-2 hour');
        //echo $before_time;

        $data=array();
        $data['nav']=$this->nav;
        $data['child_nav']='article_second_confirm';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        $orderby_name='member_commit';
        $orderby_value='DESC';


        $where=array();
        $where['admin_id']=$_SESSION['admin']['id'];
        $rs=$this->Dispatch_model->limit($length, $skipnum)->order_by($orderby_name,$orderby_value)->second_confirm($where['admin_id'],$before_time);

        //$count=count($rs);

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
            $rs_row['article_positive']=$article_tmp['positive'];
            array_push($newrs,$rs_row);
        }

        $data['rs']=$newrs;

        $data['page_total']=count($newrs);

        $this->load->view($this->template_patch.'/dispatch/article_second_confirm',$data);
    }


    public function secondconfirmreply_get(){
        $before_time=strtotime('-2 hour');
        //echo $before_time;

        $data=array();
        $data['nav']=$this->nav;
        $data['child_nav']='reply_second_confirm';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        $orderby_name='member_commit';
        $orderby_value='DESC';


        $where=array();
        $where['admin_id']=$_SESSION['admin']['id'];
        $rs=$this->Replydispatch_model->limit($length, $skipnum)->order_by('second_confirm','asc')->order_by($orderby_name,$orderby_value)->second_confirm($where['admin_id'],$before_time);

        //$count=count($rs);

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
            $rs_row['comment_positive']=$comment_tmp['positive'];
            $cuswhere=array();
            $cuswhere['id']=$comment_tmp['article_id'];
            $article_tmp=$this->List_model->get_by($cuswhere);
            $rs_row['article_title']=$article_tmp['title'];
            array_push($newrs,$rs_row);
        }

        $data['rs']=$newrs;

        $data['page_total']=count($newrs);

        $this->load->view($this->template_patch.'/dispatch/reply_second_confirm',$data);
    }


    public function setarticlesecondconfirm_post(){
        $dispatchID=$this->input->post('dispatchID');
        $dispatchSecondConfirm=$this->input->post('dispatchSecondConfirm');
        $articleID=$this->input->post('articleID');
        $positives=$this->input->post('positives');

        $cuswhere=array();
        //$cuswhere['id']=$dispatchID;
        $cuswhere['second_confirm']=$dispatchSecondConfirm;
        $this->Dispatch_model->update($dispatchID,$cuswhere);

        if($positives!=-1){
            $this->List_model->update($articleID,array('positive' => $positives));
        }

        echo '1';
    }


    public function setreplysecondconfirm_post(){
        $dispatchID=$this->input->post('dispatchID');
        $dispatchSecondConfirm=$this->input->post('dispatchSecondConfirm');
        $articleID=$this->input->post('articleID');
        $positives=$this->input->post('positives');

        $cuswhere=array();
        //$cuswhere['id']=$dispatchID;
        $cuswhere['second_confirm']=$dispatchSecondConfirm;
        $this->Replydispatch_model->update($dispatchID,$cuswhere);

        if($positives!=-1){
            $this->Onlinecomment_model->update($articleID,array('positive' => $positives));
        }

        echo '1';
    }
}