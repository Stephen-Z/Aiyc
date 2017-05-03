<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');
/**
 * Created by PhpStorm.
 * User: lzm
 * Date: 5/3/17
 * Time: 4:37 PM
 */
class Dbtest extends REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model('article/List_model','Comment_model',true);
    }

    public function index_get(){
        //prepare data
        $orderby_name='id';
        $orderby_value='DESC';

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');
        init_page_params($skipnum, $length);

        $count=$this->Comment_model->count_all();

        $rs=$this->Comment_model->limit($length,$skipnum)->order_by($orderby_name,$orderby_value)->left_join_comment();
        //$rs=$query->result_array();

        foreach ($rs as $rs_row){
            var_dump($rs_row);
            echo '<br>';
            echo '<br>';
        }
    }

}