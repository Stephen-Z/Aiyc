<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 5/3/17
 * Time: 12:42 PM
 * controller for 发表评论
 */
class Postcomment extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->path=REST_Controller::MANAGER_PATH;
        $this->load->model('article/Comment_model','Comment_model',true);
    }

    public function create_post(){
        $articleId = $this->input->post('articleid');
        $comment_content = $this->input->post('content');

        $data=array();
        $data['article_id']=$articleId;
        $data['comment_content']=$comment_content;

        if($this->Comment_model->insert($data)){
            echo '1';
        }else{
            echo '0';
        }
    }
}