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

        $cid=$this->get('cid');
        $author=$this->get('author');
        $status=$this->get('status');
        $positive=$this->get('positive');
        $reply=$this->get('reply');
        $start_time=$this->get('startTime');
        $end_time=$this->get('endTime');



        $cnrs=array('name' => '派发评论');

        $data['cnrs']=$cnrs;


        $skipnum = $this->get('skipnum');
        $length = $this->get('length');

        init_page_params($skipnum, $length);

        $where=array();

        if(!empty($cid)){
            $where['brand_id']=intval($cid);
        }

        if(!empty($author)){
            $where['author']=$author;
        }

        if($positive!='' and $positive!=2){
            $where['positive']=$positive;
        }

        if($status!=''){
            $where['status']=$status;
        }

        if($reply!=''){
            $where['reply >=']=intval($reply);
        }

        if(!empty($start_time) or !empty($end_time)){
            if(empty($start_time)){
                $start_time=2010-01-01;
            }
            if(empty($end_time)){
                $end_time=2210-01-01;
            }
            $start_time=strtotime($start_time);
            $end_time=strtotime($end_time);
            $where["release_time >= {$start_time} and release_time <= {$end_time}"]=null;
            $data['startTime']=date('Y-m-d',$start_time);
            $data['endTime']=date('Y-m-d',$end_time);
        }

        $orderby_name='id';
        $orderby_value='DESC';

        $count=$this->List_model->count_by($where);

        $rs = $this->List_model->limit($length, $skipnum)->order_by($orderby_name,$orderby_value)->get_many_by($where);

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
}