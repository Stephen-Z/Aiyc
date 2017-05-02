<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <?php
  $style_path=REST_Controller::SYSTEM_STYLE_PATH;
  $admin_path=REST_Controller::MANAGER_PATH;
  ?>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="<?php echo $this->config->base_url($style_path);?>/images/favicon.png" type="image/png">

  <title>管理系统</title>
  <link href="<?php echo $this->config->base_url($style_path);?>/css/style.default.css" rel="stylesheet">
  <link href="<?php echo $this->config->base_url($style_path);?>/css/jquery.datatables.css" rel="stylesheet">
  <link rel="stylesheet" href="<?php echo $this->config->base_url($style_path);?>/assets/lib/Font-Awesome/css/font-awesome.css"/>
  <link href="<?php echo $this->config->base_url($style_path);?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
  <link href="<?php echo $this->config->base_url($style_path);?>/css/admin.css" rel="stylesheet" />
  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="<?php echo $this->config->base_url($style_path);?>/js/html5shiv.js"></script>
  <script src="<?php echo $this->config->base_url($style_path);?>/js/respond.min.js"></script>
  <![endif]-->
  <script src="<?php echo $this->config->base_url($style_path);?>/js/jquery-1.10.2.min.js"></script>
  <script src="<?php echo $this->config->base_url($style_path);?>/js/jquery-ui-1.10.3.min.js"></script>
  <script src="<?php echo $this->config->base_url($style_path);?>/js/jquery.validate.js"></script>

  <script src="<?php echo $this->config->base_url($style_path);?>/js/uploadify/jquery.uploadify.js" type="text/javascript"></script>
  <link rel="stylesheet" type="text/css" href="<?php echo $this->config->base_url($style_path);?>/js/uploadify/uploadify.css">

</head>

<body>

<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>

<section>
	<div class="leftpanel">

    <div class="logopanel">
        <h1><a href="<?php echo $this->config->base_url();?>manager/dashboard"><span>[</span> 管理系统 <span>]</span></a></h1>
    </div><!-- logopanel -->

        <?php
        $this->config->load('admin_nav', TRUE);
        $admin_nav = $this->config->item('nav', 'admin_nav');
        ?>
        <div class="leftpanelinner">
            <h5 class="sidebartitle">管理菜单</h5>
            <ul class="nav nav-pills nav-stacked nav-bracket">
                <?php foreach($admin_nav as $nav_row):?>
                <?php if($nav_row['child']==false){ ?>
                    <li class="<?php if($nav==$nav_row['nav_name']):?>active<?php endif;?>"><a href="<?php echo $this->config->base_url().'manager/'.$nav_row['url']?>"><i class="fa <?php if(!empty($nav_row['icon'])){ echo $nav_row['icon'];}?>"></i> <span><?php echo $nav_row['name']?></span></a></li>
                <?php }else{ ?>
                    <li class="nav-parent <?php if($nav==$nav_row['nav_name']):?>nav-active active<?php endif;?>"><a href="#"><i class="fa <?php if(!empty($nav_row['icon'])){ echo $nav_row['icon'];}?>"></i> <span><?php echo $nav_row['name']?></span></a>
                        <ul class="children" style="<?php if($nav==$nav_row['nav_name']):?>display: block;<?php endif;?>">
                        <?php foreach($nav_row['child'] as $nav_child_row):?>
                        <li class="<?php if($child_nav==$nav_child_row['nav_name']):?>active<?php endif;?>"><a href="<?php echo $this->config->base_url().'manager/'.$nav_child_row['url']?>"><i class="fa fa-caret-right"></i><?php echo $nav_child_row['name']?></a></li>
                        <?php endforeach; ?>
                        </ul>
                    </li>
                <?php } ?>
                <?php endforeach; ?>
            </ul>
        </div><!-- leftpanelinner -->
  </div>

  <div class="mainpanel">

    <div class="headerbar">

      <a class="menutoggle"><i class="fa fa-bars"></i></a>

      <div class="header-right">
        <ul class="headermenu">
          <li>
            <div class="btn-group">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <img src="<?php echo $this->config->base_url($style_path);?>/images/photos/loggeduser.png" alt="" />
                <?php echo $_SESSION['admin']['name'];?>
                <span class="caret"></span>
              </button>
              <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                <li><a href="#"><i class="glyphicon glyphicon-user"></i>个人资料</a></li>
                <li><a href="<?php echo $this->config->base_url($admin_path);?>/system/main/password"><i class="glyphicon glyphicon-cog"></i> 修改密码</a></li>
                <li><a href="#"><i class="glyphicon glyphicon-question-sign"></i>帮助文档</a></li>
                <li><a href="<?php echo $this->config->base_url($admin_path);?>/auth/logout"><i class="glyphicon glyphicon-log-out"></i>注销登录</a></li>
              </ul>
            </div>
          </li>
          <li>
            <button id="chatview" class="btn btn-default tp-icon chat-icon">
                <i class="glyphicon glyphicon-comment"></i>
            </button>
          </li>
        </ul>
      </div><!-- header-right -->

    </div><!-- headerbar -->
    <script>console.log('header load complete!')</script>
