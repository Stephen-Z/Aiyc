<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 18/5/2017
 * Time: 11:01 AM
 * model for 点赞
 */
class Replylike_model extends MY_Model
{
    /** @var string 表名 */
    public $_table = 'site_task_onlinereply_like';

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



}