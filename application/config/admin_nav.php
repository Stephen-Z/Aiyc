<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config = array();

$config['nav']=array(
    array(
        'name' => '管理首页',
        'nav_name' => 'dashboard',
        'url' => 'main',
        'icon' => 'fa-tachometer',
        'child' => false
    ),
    /*stephen 工作人员界面 2017-04-27*/
    array(
        'name' => '我的任务',
        'nav_name' => 'my_mission',
        'url' => 'main',
        'icon' => 'fa-id-badge',
        'child' => array(
          array(
              'name' => '文章正负面',
              'nav_name' => 'article_goodorbad',
              'url' => 'main',
          ),
          array(
              'name' => '文章评论',
              'nav_name' => 'article_comment',
              'url' => 'main',
          ),
          array(
              'name' => '评论正负面',
              'nav_name' => 'comment_goodandbad',
              'url' => 'main',
          ),
          array(
              'name' => '评论回复',
              'nav_name' => 'comment_reply',
              'url' => 'main',
          )
        )
    ),
    array(
        'name' => '签到',
        'nav_name' => 'check_in',
        'url' => 'main',
        'icon' => 'fa-tachometer',
        'child' => false
    ),
    /*end*/
    array(
        'name' => '品牌管理',
        'nav_name' => 'brand',
        'url' => false,
        'icon' => 'fa-indent',
        'child' => array(
            array(
                'name' => '品牌列表',
                'nav_name' => 'brand_list',
                'url' => 'article/column',
            ),
            array(
                'name' => '添加品牌',
                'nav_name' => 'brand_add',
                'url' => 'article/column/add',
            ),
        ),
    ),
    array(
        'name' => '文章管理',
        'nav_name' => 'article',
        'url' => 'index',
        'icon' => 'fa-bars',
        'child' => array(
            array(
                'name' => '文章列表',
                'nav_name' => 'listing',
                'url' => 'article/listing',
            ),
        ),
    ),
    array(
        'name' => '作者管理',
        'nav_name' => 'author',
        'url' => false,
        'icon' => 'fa-book',
        'child' => array(
            array(
                'name' => '作者列表',
                'nav_name' => 'author',
                'url' => 'article/author',
            )
        ),
    ),
    array(
        'name' => '百度收录',
        'nav_name' => 'baidu',
        'url' => false,
        'icon' => 'fa-bold',
        'child' => array(
            array(
                'name' => '导入列表',
                'nav_name' => 'excel',
                'url' => 'article/baidu/excel',
            ),
            array(
                'name' => '收录结果',
                'nav_name' => 'list',
                'url' => 'article/baidu',
            ),
        ),
    ),
    array(
        'name' => '管理帐号',
        'nav_name' => 'account',
        'url' => 'system/account',
        'icon' => 'fa-user',
        'child' => false
    ),
    array(
        'name' => '系统日志',
        'nav_name' => 'log',
        'url' => 'system/log',
        'icon' => 'fa-edit',
        'child' => false
    ),
    array(
        'name' => '密码修改',
        'nav_name' => 'password',
        'url' => 'system/password',
        'icon' => 'fa-gears',
        'child' => false
    ),
    array(
        'name' => '注销登陆',
        'nav_name' => '',
        'url' => 'auth/logout',
        'icon' => ' fa-sign-out',
        'child' => false
    ),
);
