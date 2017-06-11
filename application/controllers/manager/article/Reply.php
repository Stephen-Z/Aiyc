<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 17/5/2017
 * Time: 11:49 PM
 */
class Reply extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template_path=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->path=REST_Controller::MANAGER_PATH;
        $this->nav='article';
        $this->load->model('article/List_model','List_model',true);
        $this->load->model('article/Replylike_model','Replylike_model',true);
        $this->load->model('article/Onlinecomment_model','Onlinecomment_model',true);
    }

    public function index_get(){
        $data=array();

        $data['nav']=$this->nav;
        $data['child_nav']= 'Reply_';

        $cnrs=array('name' => '回复点赞');
        $data['cnrs']=$cnrs;

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        //prepare data
        $orderby_name='order_id';
        $orderby_value='DESC';


        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        $count=$this->Onlinecomment_model->count_all();
        $rs=$this->Onlinecomment_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->join_reply_like();

        $data['rs']=$rs;
        $data['page_total']=$count;

        $this->load->view($this->template_path.'/article/reply_index',$data);
    }

    public function updatelike_post(){
        $replyid = $this->input->post('replyid');
        //$userid = $_SESSION['admin']['id'];
        $newStatus = $this->input->post('likeCount');


        $data=array();

        $data['reply_id']=$replyid;
        $data['like_count']=$newStatus;

        $where=array('reply_id' => $replyid);

        $Count=$this->Replylike_model->count_by($where);

        if($Count==0){
            if($this->Replylike_model->insert($data)){
                echo '1';
            }else{
                echo '0';
            }
        }
        else{
            if($this->Replylike_model->update_by($where,$data)){
                echo '1';
            }else{
                echo '0';
            }
        }
    }


    public function isdanger_get(){
        $data=array();

        $data['nav']=$this->nav;
        $data['child_nav']= 'reply_danger';

        $cnrs=array('name' => '高危回复');
        $data['cnrs']=$cnrs;

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        //prepare data
        $orderby_name='article_comment.is_danger,order_id';
        $orderby_value='DESC';


        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        $count=$this->Onlinecomment_model->count_all();
        $rs=$this->Onlinecomment_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->join_article();

        $data['rs']=$rs;
        $data['page_total']=$count;

        $this->load->view($this->template_path.'/article/reply_isdanger',$data);
    }


    public function updatedanager_post(){
        $articleId = $this->input->post('articleid');
        $is_danger = $this->input->post('isdanger');

        $data=array();
        $data['is_danger']=$is_danger;

        if($this->Onlinecomment_model->update($articleId,$data)){
            echo '1';
        }else{
            echo '0';
        }
    }
}