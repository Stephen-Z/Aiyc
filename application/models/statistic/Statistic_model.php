<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 5/6/2017
 * Time: 11:01 AM
 * model for dispatch system
 */
class Statistic_model extends MY_Model
{
    /** @var string 表名 */
    public $_table = 'statistic';

    protected $primary_key = 'id';

    /** @var string 默认返回数组 */
    protected $return_type = 'array';

    /** @var bool 开启软删除 */
    protected $soft_delete = TRUE;

    public $before_create = array( 'create_timestamps' );

    public $before_update = array( 'update_timestamps' );

    protected function create_timestamps($row) {
        $row['created'] = time();
        return $row;
    }

    protected function update_timestamps($row) {
        $row['updated'] = time();
        return $row;
    }


    public function add_info($user_id,$task_type,$dispatch_date){

    }


//    public function left_join_admin($adminID){
//        $this->db->select('*,dispatch.id AS Did');
//        $this->db->from('dispatch');
//        $this->db->join('admin','admin.id=dispatch.member_id AND dispatch.admin_id='.$adminID.' AND dispatch.deleted = 0 ' ,'left');
//        $query=$this->db->get();
//        return $query->result_array();
//    }
//
//    public function join_article($member_id,$operation){
//        $this->db->select('*,dispatch.id AS Did,dispatch.created AS Dcreated');
//        $this->db->from('dispatch');
//        $this->db->join('article','article.id=dispatch.article_id AND dispatch.task_done=0 AND dispatch.member_id='.$member_id.' AND dispatch.deleted = 0 AND dispatch.operation = '.$operation ,'inner');
//        $query=$this->db->get();
//        return $query->result_array();
//    }
//
//    public function admin_join_comment(){
//        $this->db->select('*,dispatch.id AS Did');
//        $this->db->from('site_task_article_comment');
//        $this->db->join('dispatch','dispatch.id = site_task_article_comment.task_id AND dispatch.deleted=0 AND site_task_article_comment.is_reply=0','inner');
//        $this->db->join('article','article.id=site_task_article_comment.article_id','left');
//        $this->db->join('admin','dispatch.member_id=admin.id','left');
//        $query=$this->db->get();
//        return $query->result_array();
//    }
//
//    public function second_confirm($adminID,$times){
//        $this->db->select('*,dispatch.id AS Did');
//        $this->db->from('dispatch');
//        $this->db->where('operation=1');
//        //$this->db->join('article','dispatch.article_id=article.id AND dispatch.task_done=3 AND dispatch.deleted=0 AND dispatch.admin_id="'.$adminID.'" AND dispatch.member_commit<'.$times);
//        $query=$this->db->get();
//        return $query->result_array();
//    }

}