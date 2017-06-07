<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 5/6/2017
 * Time: 11:01 AM
 * model for dispatch system
 */
class Dispatch_model extends MY_Model
{
    /** @var string 表名 */
    public $_table = 'dispatch';

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

    public function left_join_admin($adminID){
        $this->db->select('*,dispatch.id AS Did');
        $this->db->from('dispatch');
        $this->db->join('admin','admin.id=dispatch.member_id AND dispatch.admin_id='.$adminID.' AND dispatch.deleted = 0 ' ,'left');
        $query=$this->db->get();
        return $query->result_array();
    }

}