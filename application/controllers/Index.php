<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');

/**
 *
 * @property CI_Form_validation form_validation
 */

class Index extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->template_patch=REST_Controller::WEB_TEMPLATE_PATH;

    }

	public function index_get()
	{
        $data=array();

        echo 'hello';

    }
}
