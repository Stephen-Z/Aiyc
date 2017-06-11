<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 6/9/17
 * Time: 2:21 PM
 */
class Onlinecomment_model extends MY_Model
{
    /** @var string 表名 */
    public $_table = 'article_comment';

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

    public function join_article(){
        $this->db->select('*,article_comment.status AS Cstatus,article_comment.created AS Ccreated,article_comment.is_danger AS Cisdanger');
        $this->db->from('article_comment');
        $this->db->join('article','article.id = article_comment.article_id AND article_comment.deleted=0 ','inner');
        $query=$this->db->get();
        return $query->result_array();
    }

    public function join_reply_like(){
        $this->db->select('*,article_id AS Aid,article_comment.created AS Ccreated,site_task_onlinereply_like.like_count AS LikeCount,article_comment.is_danger AS Cisdanger');
        $this->db->from('article_comment');
        $this->db->join('site_task_onlinereply_like','article_comment.order_id = site_task_onlinereply_like.reply_id AND article_comment.deleted=0' ,'left');
        $this->db->join('article','article.id=article_comment.article_id');
        $query=$this->db->get();
        return $query->result_array();
    }
}