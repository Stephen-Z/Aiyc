<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');

/**
 *
 * @property Column_model Column_model
 */

class Column extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->patch=REST_Controller::MANAGER_PATH;
        $this->load->helper('message_encode');
        $this->load->model('article/Column_model','Column_model',true);
        $this->nav = 'brand';
    }

	public function index_get()
	{
        $data=array();
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'brand_list';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $fid=$this->get('fid');

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');

        init_page_params($skipnum, $length);

        $where=array();

        $orderby_name='id';
        $orderby_value='DESC';

        $count=$this->Column_model->count_by($where);

        $rs = $this->Column_model->limit($length, $skipnum)->order_by($orderby_name,$orderby_value)->get_many_by($where);

        $data['rs']=$rs;

        $data['page_total']=$count;

        $this->load->view($this->template_patch.'/article/column_list',$data);
	}

    public function add_get(){

        if(!empty($_SESSION['flashdata'])){
            $data=$_SESSION['flashdata'];
            unset($_SESSION['flashdata']);
        }else{
            $data=array();
        }

        $data['nav'] = $this->nav;
        $data['child_nav'] = 'brand_add';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $this->load->view($this->template_patch.'/article/column_add',$data);

    }

    public function edit_get(){

        if(!empty($_SESSION['flashdata'])){
            $data=$_SESSION['flashdata'];
            unset($_SESSION['flashdata']);
        }else{
            $data=array();
        }

        $data['nav'] = $this->nav;
        $data['child_nav'] = 'column';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();


        $data['skip_url']='/article/column';

        if(!$this->get('id')){
            $data['msg']='无ID参数值';
            $this->load->view($this->template_patch.'/public/error',$data);
            return;
        }

        $id=intval($this->get('id'));
        $info=$this->Column_model->get($id);
        if(empty($info)){
            $data['msg']='无效参数值';
            $this->load->view($this->template_patch.'/public/error',$data);
            return;
        }
        $data['id']=$id;
        $data['info']=$info;

        $this->load->view($this->template_patch.'/article/column_edit',$data);

    }

    public function create_post(){

        $data['nav'] = $this->nav;
        $data['child_nav'] = 'column';

        $this->form_validation->set_rules('name', '关键字', 'required');
       if ($this->form_validation->run() == FALSE)
        {
            $error = $this->form_validation->error_array();
            $data['error']=$error;

            $data = array_merge($data, $this->post());
            $_SESSION['flashdata']=$data;
            redirect(base_url($this->patch.'/article/column/add'));
        }

        $postdata=$this->postdata();

        $insert_id=$this->Column_model->insert($postdata);

        $data['skip_url']='/article/column';

        if($insert_id){
            //$data['msg']='提交成功'; //自定义提示
            $this->load->view($this->template_patch.'/public/success',$data);

        }else{
            $this->load->view($this->template_patch.'/public/error',$data);
        }


    }

    public function update_post(){

        $data['nav'] = $this->nav;
        $data['child_nav'] = 'column';

        $this->form_validation->set_rules('name', '关键字', 'required');

        $id=$this->post('id');

        $data['skip_url']='/article/column';

        if(empty($id)){
            $data['msg']='无ID参数值';
            $this->load->view($this->template_patch.'/public/error',$data);
            return;
        }

        if ($this->form_validation->run() == FALSE)
        {
            $error = $this->form_validation->error_array();
            $data['error']=$error;

            $data = array_merge($data, $this->post());
            $_SESSION['flashdata']=$data;
            redirect(base_url($this->patch.'/article/column/edit'));
        }

        $postdata=$this->postdata();

        $seed=intval($this->post('updated'));

        $rs = $this->Column_model->updateWithSeed($id, $postdata, $seed);

        $data['skip_url']='/article/column';

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

        $result = $this->Column_model->delete_many($ids);

        $this->response(encode_update_message($result));
    }

    public function postdata(){
        $data['name']=$this->post('name');
        return $data;
    }

}
