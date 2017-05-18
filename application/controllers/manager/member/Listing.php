<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH.'libraries/REST_Controller.php');

/**
 *
 * @property Member_model Member_model
 * @property Score_model Score_model
 * @property CI_Form_validation form_validation
 */

class Listing extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
        $this->patch=REST_Controller::MANAGER_PATH;
        $this->load->helper('message_encode');
        $this->load->model('member/Member_model','Member_model',true);
        $this->load->model('member/Score_model','Score_model',true);
        $this->nav = 'member';

    }

	public function index_get()
	{
        $data=array();
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'member_list';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');

        init_page_params($skipnum, $length);

        $where=array();

        if(!empty($cid)){
            $where['cid']=intval($cid);
        }

        $orderby_name='id';
        $orderby_value='DESC';

        $count=$this->Member_model->count_by($where);

        $rs = $this->Member_model->limit($length, $skipnum)->order_by($orderby_name,$orderby_value)->get_many_by($where);

        $data['rs']=$rs;

        $data['page_total']=$count;

        $this->load->view($this->template_patch.'/member/list',$data);
	}

    public function delete_post() {

        if ($this->form_validation->run('deletes') == FALSE) {
            $error = $this->form_validation->error_array();
            $this->response(encode_validate_fail_message($error));
        }

        $ids = $this->post('ids');

        $result = $this->Member_model->delete_many($ids);

        $this->response(encode_update_message($result));
    }

    public function edit_get($id=''){

        if(!empty($_SESSION['flashdata'])){
            $data=$_SESSION['flashdata'];
            unset($_SESSION['flashdata']);
        }else{
            $data=array();
        }        $data['nav'] = $this->nav;
        $data['child_nav'] = 'member_list';

        if(empty($id)){
            redirect(base_url($this->patch.'/member/listing'));
        }

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $data['rs']=$this->Member_model->get($id);

        if(empty($data['rs'])){
            redirect(base_url($this->patch.'/member/listing'));
        }
        $this->load->view($this->template_patch.'/member/edit',$data);

    }

    public function update_post(){

        $data['nav'] = $this->nav;
        $data['child_nav'] = 'member_list';

        $id=$this->post('id');

        $data['skip_url']='/member/listing';

        if(empty($id)){
            $data['msg']='无ID参数值';
            $this->load->view($this->template_patch.'/public/error',$data);
            return;
        }

        $this->form_validation->set_rules('score', '扣减积分', 'required|numeric|greater_than[0]');

        if ($this->form_validation->run() == FALSE)
        {
            $error = $this->form_validation->error_array();
            $data['error']=$error;

            $data = array_merge($data, $this->post());
            $_SESSION['flashdata']=$data;
            redirect(base_url($this->patch.'/member/listing/edit/'.$id));
        }

        $re_score=$this->post('score');

        $score=$this->Member_model->get($id);
        if(empty($score)){
            $data['msg']='无效ID参数值';
            $this->load->view($this->template_patch.'/public/error',$data);
            return;
        }

        if($score['score']<$re_score){
            $data['msg']='扣减积分不能大于现在积分';
            $data['skip_url']='/member/listing/edit/'.$id;
            $this->load->view($this->template_patch.'/public/error',$data);
            return;
        }

        $postdata['score']=$score['score']-$re_score;

        $seed=intval($this->post('updated'));

        $rs = $this->Member_model->updateWithSeed($id, $postdata, $seed);

        $score_data['member_id']=$id;
        $score_data['score']=$re_score;
        $this->Score_model->insert($score_data);

        $data['skip_url']='/member/listing';

        if($rs){
            //$data['msg']='提交成功'; //自定义提示
            $this->load->view($this->template_patch.'/public/success',$data);

        }else{
            $this->load->view($this->template_patch.'/public/error',$data);
        }
    }


    public function score_get()
    {
        $data=array();
        $data['nav'] = $this->nav;
        $data['child_nav'] = 'member_score';

        $data['token_name'] = $this->security->get_csrf_token_name();
        $data['hash'] = $this->security->get_csrf_hash();

        $skipnum = $this->get('skipnum');
        $length = $this->get('length');

        init_page_params($skipnum, $length);

        $where=array();

        $orderby_name='id';
        $orderby_value='DESC';

        $count=$this->Score_model->count_by($where);

        $rs = $this->Score_model->limit($length, $skipnum)->order_by($orderby_name,$orderby_value)->get_many_by($where);

        $i=0;
        foreach($rs as $rs_row){
            $rs[$i]['member']=$this->Member_model->get($rs_row['member_id']);
            $i++;
        }

        $data['rs']=$rs;

        $data['page_total']=$count;

        $this->load->view($this->template_patch.'/member/score',$data);
    }

}
