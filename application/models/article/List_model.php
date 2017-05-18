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

    public function left_join_comment(){
        $this->db->select('*,article.created AS Acreated,article.updated AS Aupdated,article.deleted AS Adeleted');
        $this->db->from('article');
        $this->db->join('article_comment','article.id = article_comment.article_id','left');
        $query=$this->db->get();
        return $query->result_array();
    }

}