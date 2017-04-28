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

class Author extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->patch=REST_Controller::MANAGER_PATH;
        $this->load->helper('message_encode');
        $this->load->model('article/Column_model','Column_model',true);
        $this->load->model('article/List_model','List_model',true);
        $this->nav = 'author';

    }

	public function index_get()
	{
        $data=array();
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'author';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $cid=$this->get('cid');

        $cnrs=$this->Column_model->get($cid);

        $data['cnrs']=$cnrs;


        $skipnum = $this->get('skipnum');
        $length = $this->get('length');

        init_page_params($skipnum, $length);

        $where=array();

        if(!empty($cid)){
            $where['brand_id']=intval($cid);
        }

        $orderby_name='id';
        $orderby_value='DESC';

        $count=$this->List_model->distinct('author')->select('author')->count_by($where);

        $rs = $this->List_model->distinct('author')->select('author')->limit($length, $skipnum)->order_by($orderby_name,$orderby_value)->get_many_by($where);

        //echo $this->db->last_query();
        $i=0;
        foreach($rs as $rs_row){
            $rs[$i]['article_count']=$this->List_model->count_by(array('author'=>$rs_row['author']));
            $rs[$i]['brand_count']=$this->List_model->distinct('brand_id')->select('brand_id')->count_by(array('author'=>$rs_row['author']));
            $rs[$i]['positive_up_count']=$this->List_model->count_by(array('author'=>$rs_row['author'],'positive'=>1));
            $rs[$i]['positive_down_count']=$this->List_model->count_by(array('author'=>$rs_row['author'],'positive'=>0));
            $i++;
        }


        $data['rs']=$rs;

        $data['page_total']=$count;

        $this->load->view($this->template_patch.'/article/author_list',$data);
	}

}
