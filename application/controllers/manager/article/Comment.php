<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');

/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 30/4/2017
 * Time: 11:04 PM
 * class for 文章评论
 */
class Comment extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template_path=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->path=REST_Controller::MANAGER_PATH;
        $this->nav='my_mission';
        $this->load->model('article/List_model','List_model',true);
        $this->load->model('article/Comment_model','Comment_model',true);
    }

    public function index_get(){
        $data=array();

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $data['nav']=$this->nav;
        $data['child_nav']='article_comment';

        $cnrs=array('name' => '文章评论');
        $data['cnrs']=$cnrs;

        //preparing data...
        $orderby_name='Aid';
        $orderby_value='DESC';

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        $user_id = $_SESSION['admin']['id'];

        $count=$this->List_model->count_all();
        $rs=$this->List_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->left_join_comment($user_id);

        $data['rs']=$rs;
        $data['page_total']=$count;

        $this->load->view($this->template_path.'/article/article_comment',$data);
    }

    //发表评论 stephen 2017-05-06
    public function create_post(){
        $articleId = $this->input->post('articleid');
        $comment_content = $this->input->post('content');
        $user_id = $_SESSION['admin']['id'];

        $data=array();
        $data['article_id']=$articleId;
        $data['content']=$comment_content;
        $data['user_id']=$user_id;

        if($this->Comment_model->insert($data)){
            //修改回复数
            $where=array();
            $where['id']=$articleId;
            $tempdata=$this->List_model->get_by($where);
            $reply=$tempdata['reply'];
            $preReply=$reply;
            $reply += 1;
            $updateData=array();
            $updateData['pre_reply']=$preReply;
            $updateData['reply']=$reply;
            $this->List_model->update_by($where,$updateData);
            echo '1';
        }else{
            echo '0';
        }
    }
}