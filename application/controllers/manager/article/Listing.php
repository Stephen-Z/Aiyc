<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');

/**
 *
 * @property Column_model Column_model
 * @property List_model List_model
 * @property Content_model Content_model
 * @property CI_Form_validation form_validation
 */

class Listing extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->patch=REST_Controller::MANAGER_PATH;
        $this->load->helper('message_encode');
        $this->load->model('article/Column_model','Column_model',true);
        $this->load->model('article/List_model','List_model',true);
        $this->load->model('article/Content_model','Content_model',true);
        $this->nav = 'article';

    }

	public function index_get()
	{
        $data=array();
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'listing';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

//        $cid=$this->get('cid');
//        $author=$this->get('author');
//        $status=$this->get('status');
//        $positive=$this->get('positive');
//        $reply=$this->get('reply');
//        $start_time=$this->get('startTime');
//        $end_time=$this->get('endTime');
        $tmpcid=$this->get('cid');
        $tmpauthor=$this->get('author');
        $tmpstatus=$this->get('status');
        $tmppositive=$this->get('positive');
        $tmpreply=$this->get('reply');
        $tmpstart_time=$this->get('startTime');
        $tmpend_time=$this->get('endTime');
        $tmpkeywords=$this->get('keyword');



        $cnrs=$this->Column_model->get($tmpcid);

        $data['cnrs']=$cnrs;


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

        //$keywords='';
        if($tmpkeywords!=''){
            $_SESSION['filter']['keyword']=$tmpkeywords;
        }
        $keywords= $_SESSION['filter']['keyword'];
        $data['form_keyword']=$_SESSION['filter']['keyword'];

        if(!empty($tmpstart_time) or !empty($tmpend_time)){
            if(empty($tmpstart_time)){
                $start_time=$_SESSION['filter']['startTime'];
            }
            if(empty($tmpend_time)){
                $end_time=$_SESSION['filter']['endTime'];
            }
            $start_time=strtotime($start_time);
            $end_time=strtotime($end_time);
            $where["release_time >= {$start_time} and release_time <= {$end_time}"]=null;
            $data['startTime']=date('Y-m-d',$start_time);
            $data['endTime']=date('Y-m-d',$end_time);
        }

        $orderby_name='id';
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

        $this->load->view($this->template_patch.'/article/article_list',$data);
	}

    public function clearfilter_get(){
        unset($_SESSION['filter']);
        $this->load->helper('url');
        redirect('/manager/article/listing','refresh');
    }

    public function clearkeyword_get(){
        $_SESSION['filter']['keyword']='';
        $this->load->helper('url');
        redirect('/manager/article/listing','refresh');
    }

    public function add_get(){

        if(!empty($_SESSION['flashdata'])){
            $data=$_SESSION['flashdata'];
            unset($_SESSION['flashdata']);
        }else{
            $data=array();
        }

        $data['nav'] = $this->nav;
        $data['child_nav'] = 'listing';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $rs=$this->Column_model->get_many_by(array('fid'=>0));

        $i=0;
        foreach($rs as $rs_row){
            $rs[$i]['child']=$this->Column_model->get_many_by(array('fid'=>$rs_row['id']));
            $i++;
        }

        $data['rs']=$rs;

        $this->load->view($this->template_patch.'/article/article_add',$data);

    }

    public function create_post(){

        $data['nav'] = $this->nav;
        $data['child_nav'] = 'listing';

        $this->form_validation->set_rules('title', '文章标题', 'required');
        $this->form_validation->set_rules('cid', '所属目录', 'required|is_natural');
        $this->form_validation->set_rules('content', '文章内容', 'required');

        if ($this->form_validation->run() == FALSE)
        {
            $error = $this->form_validation->error_array();
            $data['error']=$error;

            $data = array_merge($data, $this->post());
            $_SESSION['flashdata']=$data;
            redirect(base_url($this->patch.'/article/listing/add'));
        }

        $postdata=$this->postdata();

        $info_data=$postdata;
        unset($info_data['content']);

        $this->db->trans_start();

        $insert_id=$this->List_model->insert($info_data);
        $this->Content_model->insert(array('fid'=>$insert_id,'content'=>$postdata['content']));

        $this->db->trans_complete();

        $data['skip_url']='/article/listing';

        if ($this->db->trans_status() === TRUE){
            //$data['msg']='提交成功'; //自定义提示
            $this->load->view($this->template_patch.'/public/success',$data);
        }else{
            $this->load->view($this->template_patch.'/public/error',$data);
        }


    }


    public function edit_get(){

        if(!empty($_SESSION['flashdata'])){
            $data=$_SESSION['flashdata'];
            unset($_SESSION['flashdata']);
        }else{
            $data=array();
        }

        $data['nav'] = $this->nav;
        $data['child_nav'] = 'listing';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();


        $data['skip_url']='/article/listing';

        if(!$this->get('id')){
            $data['msg']='无ID参数值';
            $this->load->view($this->template_patch.'/public/error',$data);
            return;
        }

        $id=intval($this->get('id'));
        $info=$this->List_model->get($id);

        if(empty($info)){
            $data['msg']='无效参数值';
            $this->load->view($this->template_patch.'/public/error',$data);
            return;
        }
        $data['id']=$id;
        $data['info']=$info;
        $rs=$this->Column_model->get_many_by(array());
        
        $data['rs']=$rs;

        $this->load->view($this->template_patch.'/article/article_edit',$data);

    }

    public function update_post(){

        $data['nav'] = $this->nav;
        $data['child_nav'] = 'listing';

        $id=$this->post('id');

        $data['skip_url']='/article/listing';

        if(empty($id)){
            $data['msg']='无ID参数值';
            $this->load->view($this->template_patch.'/public/error',$data);
            return;
        }

        $this->form_validation->set_rules('status', '状态', 'is_natural');
        $this->form_validation->set_rules('positive', '正负面', 'is_natural');

        if ($this->form_validation->run() == FALSE)
        {
            $error = $this->form_validation->error_array();
            $data['error']=$error;

            $data = array_merge($data, $this->post());
            $_SESSION['flashdata']=$data;
            redirect(base_url($this->patch.'/article/listing/edit?id='.$id));
        }

        $postdata=$this->postdata();

        $info_data=$postdata;

        $seed=intval($this->post('updated'));


        $rs = $this->List_model->updateWithSeed($id, $info_data, $seed);

        $data['skip_url']='/article/listing';

        if($rs){
            //$data['msg']='提交成功'; //自定义提示
            $this->load->view($this->template_patch.'/public/success',$data);

        }else{
            $this->load->view($this->template_patch.'/public/error',$data);
        }

    }


    public function delete_post() {

        if ($this->form_validation->run('deletes') == FALSE) {
            $error = $this->form_validation->error_array();
            $this->response(encode_validate_fail_message($error));
        }

        $ids = $this->post('ids');

        $this->db->trans_start();

        $result = $this->List_model->delete_many($ids);
        $this->Content_model->delete_many_by('fid',$ids);
        $this->db->trans_complete();

        $this->response(encode_update_message($result));
    }


    public function postdata(){
        $data['brand_id']=$this->post('brand_id');
        $data['positive']=$this->post('positive');
        $data['status']=$this->post('status');
        return $data;
    }

    public function editupload_post() {
        //CKEditorFuncNum=1&langCode=zh-cn
        $funcnum = $_GET['CKEditorFuncNum'];
        $path = 'editor';
        $upload_parent_path = './uploads/';
        $config['upload_path'] = $upload_parent_path . $path;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = 1000;
        $config['max_width'] = 2048;
        $config['max_height'] = 2048;
        $config['file_name'] = time().rand(100, 999);

        if (!is_writable($upload_parent_path)) {
            $this->response(encode_exception_message('目录没有可写属性'));
        }

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path']);
        }
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('upload')) {
            $error = $this->upload->display_errors('','');
            $this->response(encode_exception_message($error));
        } else {
            $data = array('upload_data' => $this->upload->data());
            $absUrl = base_url('/uploads/'.$path.'/'.$data['upload_data']['file_name']);
            header('Content-Type: text/html; charset=UTF-8');
            $script = '<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction('.$funcnum.',"'.$absUrl.'");</script>';
            echo $script;
        }
    }



    public function isdanger_get(){
        $data=array();
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'article_danger';
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

        $orderby_name='is_danger,id';
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

        $this->load->view($this->template_patch.'/article/listing_isdanger',$data);
    }



    public function updatedanager_post(){
        $articleId = $this->input->post('articleid');
        $is_danger = $this->input->post('isdanger');

        $data=array();
        $data['is_danger']=$is_danger;

        if($this->List_model->update($articleId,$data)){
            echo '1';
        }else{
            echo '0';
        }
    }
}
