<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 6/5/17
 * Time: 10:55 AM
 * Controller for Dispatcher System
 */
class Reply extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->template_path=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->patch=REST_Controller::MANAGER_PATH;
        $this->load->helper('message_encode');
        $this->load->model('article/Column_model','Column_model',true);
        $this->load->model('article/List_model','List_model',true);
        $this->load->model('article/Content_model','Content_model',true);
        $this->load->model('article/Onlinecomment_model','Onlinecomment_model',true);
        $this->nav = 'dispatch_system';
    }

    public function replylist_get()
    {
        $data=array();

        $data['nav']=$this->nav;
        $data['child_nav']= 'dispatcher_replylist';

        $cnrs=array('name' => '派发回复');
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
        $rs=$this->Onlinecomment_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->join_reply_like();

        $data['rs']=$rs;
        $data['page_total']=$count;

        $this->load->view($this->template_path.'/dispatch/reply_replylist',$data);
    }

}