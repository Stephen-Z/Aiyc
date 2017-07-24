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

class Article_list extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->patch=REST_Controller::MANAGER_PATH;
        $this->load->helper('message_encode');
        $this->load->model('article/Column_model','Column_model',true);
        $this->load->model('article/List_model','List_model',true);
        $this->load->model('article/Content_model','Content_model',true);
        $this->nav = 'dispatch_system';
    }

    public function index_get()
    {
        $data=array();
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'dispatcher_articleList';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();


        $tmpcid=$this->get('cid');
        $tmpauthor=$this->get('author');
        $tmpstatus=$this->get('status');
        $tmppositive=$this->get('positive');
        $tmpreply=$this->get('reply');
        $tmpstart_time=$this->get('startTime');
        $tmpend_time=$this->get('endTime');
        $tmpkeywords=$this->get('keyword');


        $cnrs=array('name' => '派发评论');

        $data['cnrs']=$cnrs;

        //$data['is_post']=0;

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');

        init_page_params($skipnum, $length);

        $where=array();

        //unset($_SESSION['filter']);

        if(!array_key_exists('filter',$_SESSION)){
            $_SESSION['filter']['cid']=-1;
            //$_SESSION['filter']['author']='';
            $_SESSION['filter']['status']=0;
            $_SESSION['filter']['positive']=2;
            $_SESSION['filter']['reply']=0;
            $_SESSION['filter']['startTime']='2010-01-01';
            $_SESSION['filter']['endTime']='2210-01-01';
            $_SESSION['filter']['keyword']='';
        }
        if(!empty($tmpcid)){
            $_SESSION['filter']['cid']=$tmpcid;
        }
        if($_SESSION['filter']['cid']==-1){

        }
        else{
            $where['brand_id']=intval($_SESSION['filter']['cid']);
        }
        $data['form_brand_id']=intval($_SESSION['filter']['cid']);

//        if(!empty($tmpauthor)){
//            $_SESSION['filter']['author']=$tmpauthor;
//        }
//        $where['author']=$_SESSION['filter']['author'];
//        $data['form_author']=$_SESSION['filter']['author'];

        if($tmppositive!='' ){
            $_SESSION['filter']['positive']=$tmppositive;
        }
        if($_SESSION['filter']['positive']==2){
            //$where['positive']=array(0,1);
        }
        else{
            $where['positive']=$_SESSION['filter']['positive'];
        }
        $data['form_positive']=$_SESSION['filter']['positive'];

        if($tmpstatus!=''){
            $_SESSION['filter']['status']=$tmpstatus;
        }
        $where['status']=$_SESSION['filter']['status'];
        $data['form_status']=$_SESSION['filter']['status'];

        if($tmpreply!=''){
            $_SESSION['filter']['reply']=$tmpreply;
        }
        $where['reply >=']=intval($_SESSION['filter']['reply']);
        $data['form_reply']=intval($_SESSION['filter']['reply']);

        $keywords='';
        if($tmpkeywords!=''){
            $_SESSION['filter']['keyword']=$tmpkeywords;
        }
        $keywords= $_SESSION['filter']['keyword'];
        $data['form_keyword']=$_SESSION['filter']['keyword'];

        $start_time=$_SESSION['filter']['startTime'];
        $end_time=$_SESSION['filter']['endTime'];
        $start_time=strtotime($start_time);
        $end_time=strtotime($end_time);
        $where["release_time >= {$start_time} and release_time <= {$end_time}"]=null;
        $data['startTime']=date('Y-m-d',$start_time);
        $data['endTime']=date('Y-m-d',$end_time);
        if(!empty($tmpstart_time) or !empty($tmpend_time)){
            if(empty($tmpstart_time)){
                $start_time=$_SESSION['filter']['startTime'];
            }
            else{
                $_SESSION['filter']['startTime']=$tmpstart_time;
                $start_time=$_SESSION['filter']['startTime'];
            }
            if(empty($tmpend_time)){
                $end_time=$_SESSION['filter']['endTime'];
            }
            else{
                $_SESSION['filter']['endTime']=$tmpend_time;
                $end_time=$_SESSION['filter']['endTime'];
            }
            $start_time=strtotime($start_time);
            $end_time=strtotime($end_time);
            $where["release_time >= {$start_time} and release_time <= {$end_time}"]=null;
            $data['startTime']=date('Y-m-d',$start_time);
            $data['endTime']=date('Y-m-d',$end_time);
        }

        $orderby_name='release_time';
        $orderby_value='DESC';

        $count=$this->List_model->title_like($keywords)->count_by($where);

        $rs = $this->List_model->title_like($keywords)->limit($length, $skipnum)->order_by($orderby_name,$orderby_value)->get_many_by($where);

        $i=0;
        foreach($rs as $rs_row){
            $crs=$this->Column_model->get($rs_row['brand_id']);
            if($crs) {
                $rs[$i]['cname'] = $crs['name'];
            }else{
                $rs[$i]['cname'] = '未分类';
            }
            $i++;
        }

        $data['column']=$this->Column_model->get_all();

        $data['rs']=$rs;

        $data['page_total']=$count;

        $this->load->view($this->template_patch.'/dispatch/article_list',$data);
    }

    public function clearfilter_get(){
        unset($_SESSION['filter']);
        $this->load->helper('url');
        redirect('/manager/dispatcher/article_list','refresh');
    }

    public function clearkeyword_get(){
        $_SESSION['filter']['keyword']='';
        $this->load->helper('url');
        redirect('/manager/dispatcher/article_list','refresh');
    }


    public function todayquestion_get()
    {
        $data=array();
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'dispatcher_articleList';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();


        $tmpcid=$this->get('cid');
        $tmpauthor=$this->get('author');
        $tmpstatus=$this->get('status');
        $tmppositive=$this->get('positive');
        $tmpreply=$this->get('reply');
        $tmpstart_time=$this->get('startTime');
        $tmpend_time=$this->get('endTime');
        $tmpkeywords=$this->get('keyword');


        $cnrs=array('name' => '派发评论');

        $data['cnrs']=$cnrs;

        //$data['is_post']=0;

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');

        init_page_params($skipnum, $length);

        $where=array();

        //unset($_SESSION['filter']);

        if(!array_key_exists('filter',$_SESSION)){
            $_SESSION['filter']['cid']=-1;
            //$_SESSION['filter']['author']='';
            $_SESSION['filter']['status']=0;
            $_SESSION['filter']['positive']=2;
            $_SESSION['filter']['reply']=0;
            $_SESSION['filter']['startTime']='2010-01-01';
            $_SESSION['filter']['endTime']='2210-01-01';
            $_SESSION['filter']['keyword']='';
        }
        if(!empty($tmpcid)){
            $_SESSION['filter']['cid']=$tmpcid;
        }
        if($_SESSION['filter']['cid']==-1){

        }
        else{
            $where['brand_id']=intval($_SESSION['filter']['cid']);
        }
        $data['form_brand_id']=intval($_SESSION['filter']['cid']);

//        if(!empty($tmpauthor)){
//            $_SESSION['filter']['author']=$tmpauthor;
//        }
//        $where['author']=$_SESSION['filter']['author'];
//        $data['form_author']=$_SESSION['filter']['author'];

        if($tmppositive!='' ){
            $_SESSION['filter']['positive']=$tmppositive;
        }
        if($_SESSION['filter']['positive']==2){
            //$where['positive']=array(0,1);
        }
        else{
            $where['positive']=$_SESSION['filter']['positive'];
        }
        $data['form_positive']=$_SESSION['filter']['positive'];

        if($tmpstatus!=''){
            $_SESSION['filter']['status']=$tmpstatus;
        }
        $where['status']=$_SESSION['filter']['status'];
        $data['form_status']=$_SESSION['filter']['status'];

        if($tmpreply!=''){
            $_SESSION['filter']['reply']=$tmpreply;
        }
        $where['reply >=']=intval($_SESSION['filter']['reply']);
        $data['form_reply']=intval($_SESSION['filter']['reply']);

        $keywords='';
        if($tmpkeywords!=''){
            $_SESSION['filter']['keyword']=$tmpkeywords;
        }
        $keywords= $_SESSION['filter']['keyword'];
        $data['form_keyword']=$_SESSION['filter']['keyword'];

        $start_time=$_SESSION['filter']['startTime'];
        $end_time=$_SESSION['filter']['endTime'];
        $start_time=strtotime($start_time);
        $end_time=strtotime($end_time);
        $where["release_time >= {$start_time} and release_time <= {$end_time}"]=null;
        $data['startTime']=date('Y-m-d',$start_time);
        $data['endTime']=date('Y-m-d',$end_time);
        if(!empty($tmpstart_time) or !empty($tmpend_time)){
            if(empty($tmpstart_time)){
                $start_time=$_SESSION['filter']['startTime'];
            }
            else{
                $_SESSION['filter']['startTime']=$tmpstart_time;
                $start_time=$_SESSION['filter']['startTime'];
            }
            if(empty($tmpend_time)){
                $end_time=$_SESSION['filter']['endTime'];
            }
            else{
                $_SESSION['filter']['endTime']=$tmpend_time;
                $end_time=$_SESSION['filter']['endTime'];
            }
            $start_time=strtotime($start_time);
            $end_time=strtotime($end_time);
            $where["release_time >= {$start_time} and release_time <= {$end_time}"]=null;
            $data['startTime']=date('Y-m-d',$start_time);
            $data['endTime']=date('Y-m-d',$end_time);
        }

        $where['author']='头条问答';

        $orderby_name='release_time';
        $orderby_value='DESC';

        $count=$this->List_model->title_like($keywords)->count_by($where);

        $rs = $this->List_model->title_like($keywords)->limit($length, $skipnum)->order_by($orderby_name,$orderby_value)->get_many_by($where);

        $i=0;
        foreach($rs as $rs_row){
            $crs=$this->Column_model->get($rs_row['brand_id']);
            if($crs) {
                $rs[$i]['cname'] = $crs['name'];
            }else{
                $rs[$i]['cname'] = '未分类';
            }
            $i++;
        }

        $data['column']=$this->Column_model->get_all();

        $data['rs']=$rs;

        $data['page_total']=$count;


        //$data['vardumps']=var_dump($_SESSION['filter']['cid']);
        $this->load->view($this->template_patch.'/dispatch/article_list',$data);
    }
}