<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 5/2/17
 * Time: 6:10 PM
 * model for 登录签到
 */
class Signin_model extends MY_Model
{
    /** @var string 表名 */
    public $_table = 'sign_in';

    protected $primary_key = 'order_id';

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

    public function left_join_admin($cusdate){
        $this->db->select('*,sign_in.name as Sname');
        $this->db->from('sign_in');
        $this->db->join('admin','sign_in.user_id = admin.id AND DATE(sign_in.login_date)=DATE(now())' ,'left');
        $query=$this->db->get();
        return $query->result_array();
    }
}