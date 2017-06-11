<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 17/5/2017
 * Time: 11:49 PM
 */
class Article_like extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template_path=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->path=REST_Controller::MANAGER_PATH;
        $this->nav='article';
        $this->load->model('article/List_model','List_model',true);
        $this->load->model('article/Articlelike_model','Articlelike_model',true);
    }

    public function index_get(){
        $data=array();

        $data['nav']=$this->nav;
        $data['child_nav']='article_like';

        $cnrs=array('name' => '文章点赞');
        $data['cnrs']=$cnrs;

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        //prepare data
        $orderby_name='Aid';
        $orderby_value='DESC';
        $userid=$_SESSION['admin']['id'];

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        $count=$this->List_model->count_all();
        $rs=$this->Articlelike_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->left_join_like($userid);

        $data['rs']=$rs;
        $data['page_total']=$count;

        $this->load->view($this->template_path.'/article/article_like',$data);
    }

    public function updatelike_post(){
        $articleId = $this->input->post('articleid');
        //$userid = $_SESSION['admin']['id'];
        $newStatus = $this->input->post('likeCount');


        $data=array();

        $data['article_id']=$articleId;
        $data['like_count']=$newStatus;

        $where=array('article_id' => $articleId);

        $Count=$this->Articlelike_model->count_by($where);

        if($Count==0){
            if($this->Articlelike_model->insert($data)){
                echo '1';
            }else{
                echo '0';
            }
        }
        else{
            if($this->Articlelike_model->update_by($where,$data)){
                echo '1';
            }else{
                echo '0';
            }
        }




    }
}