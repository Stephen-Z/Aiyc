<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 5/2/17
 * Time: 11:01 PM
 * Controller for statistic
 */
class Member_statistic extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template_path=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->path=REST_Controller::MANAGER_PATH;
        $this->nav='dispatch_system';
        $this->load->model('dispatch/Dispatch_model','Dispatch_model',true);
        $this->load->model('dispatch/Replydispatch_model','Replydispatch_model',true);
        $this->load->model('auth/Auth_model','Auth_model',true);
    }

    public function index_get(){
        $data=array();

        $data['nav']=$this->nav;
        $data['child_nav']='member_statistic';

        $cnrs=array('name' => '工人统计');
        $data['cnrs']=$cnrs;

        $cuswhere=array();
        $cuswhere['operation']=0;

        $orderby_name='member_commit';
        $orderby_value='DESC';

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        //$count=$this->Signin_model->count_all();
        $rs=$this->Dispatch_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->group_by('member_id')->get_all();
        $maindata=array();
        foreach ($rs as $rs_row){
            // user name
            $user_info=$this->Auth_model->get_by(array('id'=>$rs_row['member_id']));
            // comment
            $cuswhere=array();
            $cuswhere['operation']=0;
            $cuswhere['member_id']=$rs_row['member_id'];
            $cuswhere['deleted']=0;
            $article_comment_total=$this->Dispatch_model->count_by($cuswhere);
            $reply_comment_total=$this->Replydispatch_model->count_by($cuswhere);
            $cuswhere=array();
            $cuswhere['operation']=0;
            $cuswhere['member_id']=$rs_row['member_id'];
            $cuswhere['task_done']=array(1,2,3);
            $cuswhere['deleted']=0;
            $article_comment_done=$this->Dispatch_model->count_by($cuswhere);
            $reply_comment_done=$this->Replydispatch_model->count_by($cuswhere);

            // positive
            $cuswhere=array();
            $cuswhere['operation']=1;
            $cuswhere['member_id']=$rs_row['member_id'];
            $cuswhere['deleted']=0;
            $article_positive_total=$this->Dispatch_model->count_by($cuswhere);
            $reply_positive_total=$this->Replydispatch_model->count_by($cuswhere);
            $cuswhere=array();
            $cuswhere['operation']=1;
            $cuswhere['member_id']=$rs_row['member_id'];
            $cuswhere['task_done']=array(1,2,3);
            $cuswhere['deleted']=0;
            $article_positive_done=$this->Dispatch_model->count_by($cuswhere);
            $reply_positive_done=$this->Replydispatch_model->count_by($cuswhere);

            $tmpItem=array();
            $tmpItem['member_id']=$rs_row['member_id'];
            $tmpItem['member_name']=$user_info['name'];
            $tmpItem['article_comment_total']=$article_comment_total;
            $tmpItem['article_comment_done']=$article_comment_done;

            $tmpItem['article_positive_total']=$article_positive_total;
            $tmpItem['article_positive_done']=$article_positive_done;

            $tmpItem['reply_comment_total']=$reply_comment_total;
            $tmpItem['reply_comment_done']=$reply_comment_done;

            $tmpItem['reply_positive_total']=$reply_positive_total;
            $tmpItem['reply_positive_done']=$reply_positive_done;

            array_push($maindata,$tmpItem);
//            echo $article_comment_done.'/'.$article_comment_total;
//            echo $reply_comment_done.'/'.$reply_comment_total;
//            echo $article_positive_done.'/'.$article_positive_total;
//            echo $reply_positive_done.'/'.$reply_positive_total;
        }

//        echo $article_comment_done.'/'.$article_comment_total;
//        echo $reply_comment_done.'/'.$reply_comment_total;
//        echo $article_positive_done.'/'.$article_positive_total;
//        echo $reply_positive_done.'/'.$reply_positive_total;
        $data['rs']=$maindata;
        $data['page_total']=count($maindata);


        $this->load->view($this->template_path.'/statistic/member_statistic',$data);
    }
}