<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 5/3/17
 * Time: 1:19 PM
 * model for 评论
 */
class Comment_model extends MY_Model
{
    /** @var string 表名 */
    public $_table = 'site_task_article_comment';

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

    public function left_join_reply($member_id){
        $this->db->select('*,article_comment.status AS Cstatus,article_comment.created AS Ccreated,article_comment.is_danger AS Cisdanger');
        $this->db->from('site_task_article_comment');
        $this->db->join('reply_dispatch','reply_dispatch.reply_id=site_task_article_comment.reply_id AND reply_dispatch.deleted=0 AND site_task_article_comment.is_reply=1 AND site_task_article_comment.user_id='.$member_id,'inner');
        $this->db->join('article_comment','site_task_article_comment.reply_id = article_comment.order_id ','left');
        $query=$this->db->get();
        return $query->result_array();
    }

    public function admin_join_reply(){
        $this->db->select('*,article_comment.status AS Cstatus,article_comment.created AS Ccreated,article_comment.is_danger AS Cisdanger,site_task_article_comment.id AS Aid,,site_task_article_comment.comment_status AS Tstatus');
        $this->db->from('site_task_article_comment');
        $this->db->join('reply_dispatch','reply_dispatch.reply_id=site_task_article_comment.reply_id AND reply_dispatch.deleted=0','inner');
        $this->db->join('article_comment','site_task_article_comment.reply_id = article_comment.order_id AND site_task_article_comment.is_reply=1  ','left');
        $this->db->join('article','article_comment.article_id=article.id','left');
        $query=$this->db->get();
        return $query->result_array();
    }
}