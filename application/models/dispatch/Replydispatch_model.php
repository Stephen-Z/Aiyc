<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 5/6/2017
 * Time: 11:01 AM
 * model for dispatch system
 */
class Replydispatch_model extends MY_Model
{
    /** @var string 表名 */
    public $_table = 'reply_dispatch';

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

    public function left_join_comment($memberID,$operation){
        $this->db->select('*,reply_dispatch.id AS Did,reply_dispatch.created AS Dcreated,article_comment.positive AS Cpositive');
        $this->db->from('reply_dispatch');
        $this->db->join('article_comment','article_comment.order_id=reply_dispatch.reply_id AND reply_dispatch.task_done=0 AND reply_dispatch.member_id='.$memberID.' AND reply_dispatch.deleted = 0 AND reply_dispatch.operation='.$operation ,'inner');
        $this->db->join('article','article.id=article_comment.article_id');
        $query=$this->db->get();
        return $query->result_array();
    }


}