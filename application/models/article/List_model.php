<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class List_model extends MY_Model {

    /** @var string 表明 */
    public $_table = 'article';

    /** @var string 默认返回数组 */
    protected $return_type = 'array';

    /** @var bool 开启软删除 */
    protected $soft_delete = FALSE;

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

    public function left_join_comment($user_id){
        $this->db->select('*,article.created AS Acreated,article.updated AS Aupdated,article.deleted AS Adeleted,article.id AS Aid');
        $this->db->from('site_task_article_comment');
        $this->db->join('dispatch','dispatch.article_id = site_task_article_comment.article_id AND dispatch.deleted=0 AND site_task_article_comment.is_reply=0 AND site_task_article_comment.user_id='.$user_id,'inner');
        $this->db->join('article','article.id=site_task_article_comment.article_id','left');
        $query=$this->db->get();
        return $query->result_array();
    }

    public function admin_join_comment(){
        $this->db->select('*,article.created AS Acreated,article.updated AS Aupdated,article.deleted AS Adeleted,article.id AS Aid');
        $this->db->from('site_task_article_comment');
        $this->db->join('dispatch','dispatch.article_id = site_task_article_comment.article_id AND dispatch.deleted=0 AND site_task_article_comment.is_reply=0','inner');
        $this->db->join('article','article.id=site_task_article_comment.article_id','left');
        $query=$this->db->get();
        return $query->result_array();
    }

}