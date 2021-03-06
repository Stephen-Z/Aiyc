<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');

/**
 *
 * @property Auth_model Auth_model
 * @property Log_model Log_model
 */

class Auth extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->patch=REST_Controller::MANAGER_PATH;
        $this->load->model('auth/Auth_model','Auth_model',true);
        $this->load->model('system/Log_model','Log_model',true);
        //table of sign_in model
        $this->load->model('auth/Signin_model','Signin_model',true);
    }

	public function index_get()
	{
        $data=array();
        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();
        $this->load->view($this->template_patch.'/auth/index',$data);
	}

    public function login_post() {

        if ($this->form_validation->run('login_admin') == FALSE)
        {
            $error = $this->form_validation->error_array();
            print_r($error);
        }

        $name=$this->post('name');
        $password=md5($this->post('password'));

        $where=array();
        $where['name']=$name;
        $rs=$this->Auth_model->get_by($where);

        if(!empty($rs['phone'])){
            $code=$this->post('code');
            if(empty($code)){
                echo "<script>alert('后台已绑定手机，请输入手机验证码');location.href='" . base_url("manager/auth") . "';</script>";
                exit;
            }
            if($code!=$_SESSION['code']){
                echo "<script>alert('手机验证码不匹配');location.href='" . base_url("manager/auth") . "';</script>";
                exit;
            }
        }

        if(!$rs){

            redirect(base_url($this->patch)."/auth");
        }

        $where['password']=$password;
        $rs=$this->Auth_model->get_by($where);

        if($rs){
            $url=$this->get('url');
            $_SESSION['admin']=$rs;
            $_SESSION['flashdata']=array();
            if(empty($url)){
                $log_data['name']=$_SESSION['admin']['name'];
                $log_data['event']='登陆';
                $log_data['ip']=$this->input->ip_address();;
                $this->Log_model->insert($log_data);

                /*登录签到 stephen 2017-05-02*/
                //redirect(base_url($this->patch)."/main");
                if($this->check_sign_in($rs['id'],$rs['name'],$rs['is_admin'])){
                    redirect(base_url($this->patch)."/main");
                }
                else{
                    echo "<script>alert('签到失败');location.href='" . base_url("manager/auth") . "';</script>";
                    exit;
                }


            }else{
                redirect($url);
            }
        }else{
            redirect(base_url($this->patch)."/auth");
        }
    }
    /*登录签到 stephen 2017-05-02*/
    private function check_sign_in($user_id,$user_name,$isadmin){
        $current_date=date('Y-m-d');
        //echo '<script>alert("'.$user_id.'")</script>';

        $cuswhere=array();
        $cuswhere['login_date']=$current_date;
        $cuswhere['user_id']=$user_id;

        $rs=$this->Signin_model->get_by($cuswhere);

        $data = array();
        $data['user_id'] = $user_id;
        $data['name'] = $user_name;
        $data['is_admin']=$isadmin;
        $data['logout']=0;

        if(!$rs) {
            $data['login_date'] = $current_date;

            if ($this->Signin_model->insert($data)) {
                return true;
            } else {
                return false;
            }
        }else{
            $this->Signin_model->update($rs['order_id'],$data);
            return true;
        }
    }
    /*登录签到 stephen 2017-05-02*/

    public function logout_get() {
        $user_id=$_SESSION['admin']['id'];
        unset($_SESSION['admin']);
        $this->Signin_model->update_by(array('user_id' => $user_id),array('logout' => 1));
        redirect(base_url($this->patch)."/auth");
    }

    public function sms_get($name=''){

        header('Content-type: application/json');

        if(empty($name)){
            $msg['msg']='登陆名为空';
            echo json_encode($msg);
        }else {
            $rs = $this->Auth_model->get_by(array('name' => $name));
            if (empty($rs)) {
                $msg['msg'] = '不存在该管理员';
                echo json_encode($msg);
            } else {
                if (empty($rs['phone'])) {
                    $msg['msg'] = '未绑定手机号码,可以直接登陆';
                    echo json_encode($msg);
                } else {
                    $sms = $this->code($rs['phone']);
                    if ($sms['code'] == 0) {
                        $msg['msg'] = '发送成功';
                        echo json_encode($msg);
                    } else {
                        $msg['msg'] =  $sms['msg'];
                        echo json_encode($msg);
                    }
                }
            }
        }
    }

    private function code($phone){

        $code=rand(1000,9999);

        $ch = curl_init();
        // 必要参数
        $apikey = "dbdbd4d94031ea95d359ebffd3695e47"; //修改为您的apikey(https://www.yunpian.com)登录官网后获取
        $mobile = "{$phone}"; //请用手机号代替
        $text="【管理系统】您的验证码是".$code;
        // 发送短信
        $data=array('text'=>$text,'apikey'=>$apikey,'mobile'=>$mobile);
        curl_setopt ($ch, CURLOPT_URL, 'https://sms.yunpian.com/v2/sms/single_send.json');
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        $json_data = curl_exec($ch);
        //解析返回结果（json格式字符串）
        $array = json_decode($json_data,true);

        if($array['code']==0){
            $_SESSION['code']=$code;
        }

        return $array;

    }
}
