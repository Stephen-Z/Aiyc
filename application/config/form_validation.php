<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array();

//common validation

$config['id'] = array(
    array(
        'field' => 'id',
        'label' => 'id',
        'rules' => 'required|is_natural_no_zero'
    )
);

$config['updated'] = array(
    array(
        'field' => 'updated',
        'label' => 'updated',
        'rules' => 'required'
    )
);

$config['ids'] = array(
    array(
        'field' => 'ids[]',
        'label' => 'ids[]',
        'rules' => 'required|is_natural_no_zero'
    )
);

$config['deletes'] = $config['ids'];

$config['login_admin'] = array(
    array(
        'field' => 'name',
        'label' => '管理员',
        'rules' => 'trim|required'
    ),
    array(
        'field' => 'password',
        'label' => '密码',
        'rules' => 'trim|required'
    )

);


$config['admin_password'] = array(
    array(
        'field' => 'oldpassword',
        'label' => '旧密码',
        'rules' => 'trim|required|min_length[1]|max_length[20]|callback_old_password'
    ),
    array(
        'field' => 'password',
        'label' => '新密码',
        'rules' => 'trim|required|min_length[6]|max_length[20]'
    ),
    array(
        'field' => 'passconf',
        'label' => '密码确认',
        'rules' => 'trim|required|matches[password]'
    )
);



