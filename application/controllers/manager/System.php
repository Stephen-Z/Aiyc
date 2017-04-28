<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');

/**
 * class System
 * @property Admin_model Admin_model
 * @property Log_model Log_model
 * @property CI_Form_validation form_validation
 */

class System extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->patch=REST_Controller::MANAGER_PATH;
        $this->load->library('form_validation');
        $this->load->helper('pager');
        $this->load->helper('message_encode');
        $this->load->model('system/Admin_model','Admin_model',true);
        $this->load->model('system/Log_model','Log_model',true);
        $this->nav = 'password';
    }

    public function password_get(){
        if(!empty($_SESSION['flashdata'])){
            $data=$_SESSION['flashdata'];
            unset($_SESSION['flashdata']);
        }else{
            $data=array();
        }
        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'FALSE';

        $this->load->view($this->template_patch.'/system/password',$data);
    }

    public function update_password_post(){

        $data['nav'] = $this->nav;
        $data['child_nav'] = 'FALSE';

        if ($this->form_validation->run('admin_password') == FALSE)
        {
            $error = $this->form_validation->error_array();

            $data['error']=$error;

            $data = array_merge($data, $this->post());
            $_SESSION['flashdata']=$data;
            redirect(base_url($this->patch.'/system/password'));

        }

        $password=md5($this->post('password'));
        $admin=$_SESSION['admin']['name'];
        $name['name']=$admin;

        $pass['password']=$password;
        $this->Admin_model->update_by($name,$pass);

        $data['skip_url']='/system/main/password';

        if($this->Admin_model->affected_rows()){
            $this->load->view('admin/public/success',$data);
        }else{
            $this->load->view('admin/public/error',$data);
        }

    }

    public function old_password($str)
    {
        $data['name']=$_SESSION['admin']['name'];
        $admin=$this->Admin_model->select('password')->get_by($data);
        if (md5($str) != $admin['password'])
        {
            $this->form_validation->set_message('old_password', '%s 不正确');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function account_get(){

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();
        $data['nav'] = 'account';
        $data['child_nav'] = 'FALSE';

        if($_SESSION['admin']['name']!='admin'){
            echo "<script>alert('只有总管理员才有此权限!');location.href='" . base_url("manager/main") . "';</script>";
        }


        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);
        $where=array();
        $count=$this->Admin_model->count_by($where);

        $rs=$this->Admin_model->limit($length, $skipnum)->order_by('id','desc')->get_many_by($where);

        $data['page_total']=$count;

        $data['rs']=$rs;

        $this->load->view($this->template_patch.'/system/account',$data);
    }

    public function add_account_get(){
        if(!empty($_SESSION['flashdata'])){
            $data=$_SESSION['flashdata'];
            unset($_SESSION['flashdata']);
        }else{
            $data=array();
        }
        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();
        $data['nav'] = 'account';
        $data['child_nav'] = 'FALSE';

        $this->load->view($this->template_patch.'/system/add_account',$data);
    }

    public function save_account_post(){

        $data['nav'] = 'account';
        $data['child_nav'] = 'FALSE';

        $this->form_validation->set_rules('name', '帐号', 'trim|required|is_unique[admin.name]');
        $this->form_validation->set_rules('phone', '手机', 'trim|exact_length[11]');
        $this->form_validation->set_rules('password', '密码', 'trim|required');
        $this->form_validation->set_rules('passconf', '确认密码', 'trim|required|matches[password]');

        if ($this->form_validation->run() == FALSE)
        {
            $error = $this->form_validation->error_array();
            $data['error']=$error;

            $data = array_merge($data, $this->post());
            $_SESSION['flashdata']=$data;
            redirect(base_url($this->patch.'/system/add_account'));
        }

        $name=$this->post('name');
        $phone=$this->post('phone');
        $password=$this->post('password');

        $post_data['name']=$name;
        $post_data['password']=md5($password);
        if(!empty($phone)){
            $post_data['phone']=$phone;
        }

        $insert_id=$this->Admin_model->insert($post_data);

        $data['skip_url']='/system/account';


        if ($insert_id){
            //$data['msg']='提交成功'; //自定义提示
            $this->load->view($this->template_patch.'/public/success',$data);
        }else{
            $this->load->view($this->template_patch.'/public/error',$data);
        }

    }

    public function edit_account_get($id=''){

        if(empty($id)){
            redirect(base_url("manager/main"));
        }

        $id=intval($id);

        if(!empty($_SESSION['flashdata'])){
            $data=$_SESSION['flashdata'];
            unset($_SESSION['flashdata']);
        }else{
            $data=array();
        }

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();
        $data['nav'] = 'account';
        $data['child_nav'] = 'FALSE';

        $rs=$this->Admin_model->get($id);

        $data['rs']=$rs;

        $this->load->view($this->template_patch.'/system/edit_account',$data);
    }


    public function update_account_post(){

        $data['nav'] = 'account';
        $data['child_nav'] = 'FALSE';

        $this->form_validation->set_rules('id', 'id', 'trim|required|is_natural');
        $this->form_validation->set_rules('phone', '手机', 'trim|exact_length[11]');
        $password=$this->post('password');

        if($password) {
            $this->form_validation->set_rules('password', '密码', 'trim|required');
            $this->form_validation->set_rules('passconf', '确认密码', 'trim|required|matches[password]');
        }

        if ($this->form_validation->run() == FALSE)
        {
            $error = $this->form_validation->error_array();
            $data['error']=$error;

            $data = array_merge($data, $this->post());
            $_SESSION['flashdata']=$data;
            redirect(base_url($this->patch.'/system/add_account'));
        }

        $id=$this->post('id');
        $phone=$this->post('phone');
        $password=$this->post('password');

        $post_data=array();

        if(!empty($password)) {
            $post_data['password'] = md5($password);
        }
        if(!empty($phone)){
            $post_data['phone']=$phone;
        }

        $insert_id=$this->Admin_model->update($id,$post_data);

        $data['skip_url']='/system/account';


        if ($insert_id){
            //$data['msg']='提交成功'; //自定义提示
            $this->load->view($this->template_patch.'/public/success',$data);
        }else{
            $this->load->view($this->template_patch.'/public/error',$data);
        }

    }

    public function del_admin_get($id=''){
        if(empty($id)){
            redirect(base_url('manager/main'));
        }

        $rs=$this->Admin_model->get($id);
        if(empty($rs)){
            redirect(base_url('manager/main'));
        }

        if($rs['name']=='admin'){
            echo "<script>alert('无法删除总管理员!');location.href='" . base_url("manager/system/account") . "';</script>";
            exit;
        }

        $this->Admin_model->delete($id);
        echo "<script>alert('删除成功!');location.href='" . base_url("manager/system/account") . "';</script>";

    }


    public function log_get(){

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();
        $data['nav'] = 'log';
        $data['child_nav'] = 'FALSE';

        if($_SESSION['admin']['name']!='admin'){
            echo "<script>alert('只有总管理员才有此权限!');location.href='" . base_url("manager/main") . "';</script>";
            exit;
        }


        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);
        $where=array();
        $count=$this->Log_model->count_by($where);

        $rs=$this->Log_model->limit($length, $skipnum)->order_by('id','desc')->get_many_by($where);

        $data['page_total']=$count;

        $data['rs']=$rs;

        $this->load->view($this->template_patch.'/system/log',$data);
    }


}