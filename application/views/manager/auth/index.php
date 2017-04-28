<!DOCTYPE html>
<html lang="en">
<?php
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
;?>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="">
  <link rel="shortcut icon" href="<?php echo $this->config->base_url($style_path);?>/images/favicon.png" type="image/png">

  <title>后台管理系统</title>

  <link href="<?php echo $this->config->base_url($style_path);?>/css/style.default.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="<?php echo $this->config->base_url($style_path);?>/js/html5shiv.js"></script>
  <script src="<?php echo $this->config->base_url($style_path);?>/js/respond.min.js"></script>
  <![endif]-->
</head>

<body class="signin">

<!-- Preloader -->
<div id="preloader">
    <div id="status"><i class="fa fa-spinner fa-spin"></i></div>
</div>

<section>
  
    <div class="signinpanel">
        
        <div class="row">
            
            <div class="col-md-7">
                
                <div class="signin-info">
                    <div class="logopanel">
                        <h1><span>[</span>管理系统<span>]</span></h1>
                    </div><!-- logopanel -->
                
                    <div class="mb20"></div>
                
                    <h5><strong>欢迎使用后台管理系统</strong></h5>
                    <ul>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> 让你满意后台布局</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> 有效的HTML5/CSS3</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> 当然，更好的体验请使用Chrome,Firefox,IE10以上版本</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> 为你准备的</li>
                        <li><i class="fa fa-arrow-circle-o-right mr5"></i> 还有许多许多...</li>
                    </ul>
                    <div class="mb20"></div>
                </div><!-- signin0-info -->
            
            </div><!-- col-sm-7 -->
            
            <div class="col-md-5">
                
                <form class="form-signin" name="loginform" method="post" id="loginform" action="<?php echo $this->config->base_url($admin_path);?>/auth/login">
                    <h4 class="nomargin">管理员登录</h4>
                    <input type="hidden" name="<?php echo $token_name;?>" value="<?php echo $hash;?>" />
                    <input type="text" name="name" class="form-control uname" placeholder="Username" />
                    <input type="password" name="password" class="form-control pword" placeholder="Password" />
                    <input type="text" name="code" class="form-control" placeholder="验证码,无绑定可为空" />
                    <a class="smss" href="javascript:void (0)" style="padding-top: 15px;">发送验证码</a>
                    <button class="btn btn-success btn-block">登录</button>
                </form>
            </div><!-- col-sm-5 -->
            
        </div><!-- row -->
        
        <div class="signup-footer">
            <div class="pull-left">
                &copy; 2015. All Rights Reserved. BUKSON Bootstrap 3
            </div>
            <div class="pull-right">
                Created By: <a href="http://www.bukson.com/" target="_blank">BUKSON</a>
            </div>
        </div>
        
    </div><!-- signin -->
  
</section>


<!-- js placed at the end of the document so the pages load faster -->
<script src="<?php echo $this->config->base_url($style_path);?>/js/jquery-1.10.2.min.js"></script>
<script src="<?php echo $this->config->base_url($style_path);?>/js/jquery-migrate-1.2.1.min.js"></script>
<script src="<?php echo $this->config->base_url($style_path);?>/js/bootstrap.min.js"></script>
<script src="<?php echo $this->config->base_url($style_path);?>/js/modernizr.min.js"></script>
<script src="<?php echo $this->config->base_url($style_path);?>/js/retina.min.js"></script>

<script src="<?php echo $this->config->base_url($style_path);?>/js/custom.js"></script>
<script src="<?php echo $this->config->base_url($style_path);?>/js/jquery.validate.js"></script>
<script src="<?php echo $this->config->base_url($style_path);?>/js/bootstrap.min.js"></script>
<script>

    $('.smss').click(function(){
        var name=$('.uname').val();
        $.get("<?php echo base_url("manager/auth/sms")?>/"+name, function(result){
            alert(result.msg);
        });
    });

$("#loginform").validate({
	    rules: {
	    	 username:{required:true},
	    	 password:{required:true}
	    },
	   messages: {
	        username: {required: "请输入用户名"},
	        password: {required: "请输入密码"}
	       
	    }

});

</script>
</html>
