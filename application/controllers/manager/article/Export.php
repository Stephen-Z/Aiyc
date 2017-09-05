<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');

/**
 *
 * @property Column_model Column_model
 * @property List_model List_model
 * @property Content_model Content_model
 * @property CI_Form_validation form_validation
 * @property Comment_model Comment_model
 * @property Dispatch_model Dispatch_model
 */

class Export extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->template_path=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->patch=REST_Controller::MANAGER_PATH;
        $this->load->helper('message_encode');
        $this->load->model('article/List_model','List_model',true);
        $this->load->model('article/Comment_model','Comment_model',true);
        $this->load->model('article/Column_model','Column_model',true);
        $this->load->model('article/Content_model','Content_model',true);
        $this->load->model('dispatch/Dispatch_model','Dispatch_model',true);
        $this->nav = 'article';

    }

	public function comment_get()
    {
        $data=array();
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'export_comment';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $cid=$this->get('cid');
        $author=$this->get('author');
        $status=$this->get('status');
        $positive=$this->get('positive');
        $reply=$this->get('reply');
        $start_time=$this->get('startTime');
        $end_time=$this->get('endTime');



        $cnrs=$this->Column_model->get($cid);

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


        //$where["id"]=39138;
        $tmprs = $this->Comment_model->group_by('article_id')->get_many_by(array('comment_status'=>3));
        $articleIDList = array();
        foreach ($tmprs as $item){
            array_push($articleIDList,$item['article_id']);
        }

        $where['id']=$articleIDList;

        $orderby_name='reply,id';
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

        $this->load->view($this->template_path.'/article/export_comment',$data);
    }


    public function Requestexport_post(){
        $article_id=$this->input->post('articleID');

        $article_tmp=$this->List_model->get_by(array('id'=>$article_id));
        $article_title=$article_tmp['title'];
        $article_url=$article_tmp['url'];

       // echo var_dump($article_tmp);

        $dispatch_tmp=$this->Dispatch_model->get_many_by(array('article_id'=>$article_id,'task_done'=>3));

        //echo var_dump($dispatch_tmp);

        $taskIDarray=array();
        foreach ($dispatch_tmp as $item){
            array_push($taskIDarray,$item['id']);
        }

        $rs=$this->Comment_model->get_many_by(array('task_id'=>$taskIDarray));

        //echo var_dump($rs);
//        echo $article_id;

        $files=fopen('Export_dir/文章工人评论导出(id)'.$article_id,'wb');
        fwrite($files,$article_url.'('.$article_title.')'."\r\n".PHP_EOL);
        foreach ($rs as $rs_row){
            fwrite($files,$rs_row['content']."\r\n".PHP_EOL);
        }
        fclose($files);

        echo $article_id;

    }
}
