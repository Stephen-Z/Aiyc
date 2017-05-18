<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array();

$config['nav']=array(
    array(
        'name' => '会员控制中心',
        'nav_name' => 'main',
        'url' => 'main',
        'icon' => 'fa-tachometer',
        'child' => false
    ),
    array(
        'name' => '转运服务',
        'nav_name' => 'service',
        'url' => 'index',
        'icon' => 'fa-rocket',
        'child' => array(
            array(
                'name' => '我的包裹',
                'nav_name' => 'list',
                'url' => 'service',
            ),
            array(
                'name' => '预报包裹',
                'nav_name' => 'add',
                'url' => 'service/add',
            ),
            array(
                'name' => '问题件',
                'nav_name' => 'abnormal',
                'url' => 'service/abnormal',
            ),
            array(
                'name' => '我的运单',
                'nav_name' => 'order',
                'url' => 'service/order',
            ),
        ),
    ),
    array(
        'name' => '财务中心',
        'nav_name' => 'trade',
        'url' => false,
        'icon' => 'fa-money',
        'child' => array(
            array(
                'name' => '交易记录',
                'nav_name' => 'record',
                'url' => 'service/record',
            ),
            array(
                'name' => '积份扣除记录',
                'nav_name' => 'score',
                'url' => 'service/score',
            ),
        ),
    ),
    array(
        'name' => '帐户资料',
        'nav_name' => 'users',
        'url' => false,
        'icon' => 'fa-user',
        'child' => array(
            array(
                'name' => '收货地址',
                'nav_name' => 'address',
                'url' => 'address',
            ),
        ),
    ),
    array(
        'name' => '密码修改',
        'nav_name' => 'password',
        'url' => 'main/password',
        'icon' => 'fa-gears',
        'child' => false
    ),
    array(
        'name' => '注销登陆',
        'nav_name' => '',
        'url' => 'login/logout',
        'icon' => ' fa-sign-out',
        'child' => false
    ),
);