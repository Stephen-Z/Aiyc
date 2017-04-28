<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');

/**
 *
 * @property Test_model Test_model
 */

class Main extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->patch=REST_Controller::MANAGER_PATH;
        $this->load->helper('message_encode');
        $this->nav = 'dashboard';

    }

	public function index_get()
	{
        $data=array();
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'FALSE';

        $this->load->view($this->template_patch.'/main/dashboard',$data);
	}

    public function security_get(){
        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();
        $this->response(encode_success_message($data, 'object'));
    }
}
