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
        'name' => '派单系统',
        'nav_name' => 'dispatch_system',
        'url' => 'dispatcher/dispatch_system',
        'icon' => 'fa-tachometer',
        'child' => array(
            array(
                'name' => '文章列表',
                'nav_name' => 'dispatcher_articleList',
                'url' => 'dispatcher/article_list',
            ),
            array(
                'name' => '评论列表',
                'nav_name' => 'dispatcher_commentList',
                'url' => 'dispatcher/comment_list',
            ),
            array(
                'name' => '我的派单',
                'nav_name' => 'dispatched_list',
                'url' => 'dispatcher/dispatched',
            ),
        )

    ),

    /*工作人员界面 end*/
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
        'url' => false,
        'icon' => 'fa-user',
        'child' => array(
            array(
                'name' => '管理员',
                'nav_name' => 'admin_account',
                'url' => 'system/account',
            ),
            array(
                'name' => '工人',
                'nav_name' => 'member_account',
                'url' => 'system/member',
            ),
            array(
                'name' => '工人签到',
                'nav_name' => 'sign_in',
                'url' => 'signin/signinstatus',
                'icon' => 'fa-tachometer',
                'child' => false
            ),
        ),
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

$config['member_nav']=array(
    array(
        'name' => '我的任务',
        'nav_name' => 'my_mission',
        'url' => 'main',
        'icon' => 'fa-id-badge',
        'child' => array(
            array(
                'name' => '工作台',
                'nav_name' => 'workspace',
                'url' => 'main',
            ),
            array(
                'name' => '文章正负面',
                'nav_name' => 'article_goodORbad',
                'url' => 'article/goodorbad',
            ),
            array(
                'name' => '文章评论',
                'nav_name' => 'article_comment',
                'url' => 'article/comment',
            ),
            array(
                'name' => '评论正负面',
                'nav_name' => 'comment_goodandbad',
                'url' => 'article/comment_positive',
            ),
            array(
                'name' => '评论回复',
                'nav_name' => 'comment_reply',
                'url' => 'article/comment_reply',
            ),
            array(
                'name' => '点赞',
                'nav_name' => 'article_like',
                'url' => 'article/article_like',
            )
        )
    ),
);
