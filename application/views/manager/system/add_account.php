<?php
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>

<?php
if(empty($error)) {
    $error = array();
}
?>

<div class="pageheader">
  <h2><i class="fa fa-bars"></i> 系统设置 <span>添加管理员</span></h2>
  <div class="breadcrumb-wrapper">
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->config->base_url($admin_path);?>/main">管理首页</a></li>
        <li><a href="<?php echo $this->config->base_url($admin_path);?>/system/add_account">添加管理员</a></li>
      <li class="active">添加管理员</li>
    </ol>
  </div>
</div>
<section class="contentpanel">
 <div class="panel">
  <div class="panel-body">
    <form class="form-horizontal tasi-form form-bordered" name="adminform" id="adminform" method="post" action="<?php echo $this->config->base_url($admin_path);?>/system/save_account">
        <input type="hidden" name="<?php echo $token_name;?>" value="<?php echo $hash;?>" />
        <div class="form-group <?php if(array_key_exists('name',$error)): echo 'has-error'; endif; ?>">
            <label class="col-sm-2 control-label">帐号</label>
            <div class="col-sm-4">
                <input class="form-control" type="text" name="name" value="<?php echo @$name?>">
                <?php
                if(array_key_exists('name',$error)){
                    echo '<label for="code" class="error">'.$error['name'].'</label>';
                }
                ?>
            </div>
        </div>

        <div class="form-group <?php if(array_key_exists('phone',$error)): echo 'has-error'; endif; ?>">
            <label class="col-sm-2 control-label">手机号码</label>
            <div class="col-sm-4">
                <input class="form-control" type="text" name="phone" value="<?php echo @$phone?>">
                <?php
                if(array_key_exists('phone',$error)){
                    echo '<label for="code" class="error">'.$error['phone'].'</label>';
                }
                ?>
            </div>
        </div>

        <div class="form-group <?php if(array_key_exists('password',$error)): echo 'has-error'; endif; ?>">
            <label class="col-sm-2 control-label">密码</label>
            <div class="col-sm-4">
                <input class="form-control" type="password" name="password" value="<?php echo @$password?>">
                <?php
                if(array_key_exists('password',$error)){
                    echo '<label for="code" class="error">'.$error['password'].'</label>';
                }
                ?>
            </div>
        </div>

        <div class="form-group <?php if(array_key_exists('passconf',$error)): echo 'has-error'; endif; ?>">
            <label class="col-sm-2 control-label">确认密码</label>
            <div class="col-sm-4">
                <input class="form-control" type="password" name="passconf" value="<?php echo @$passconf?>">
                <?php
                if(array_key_exists('passconf',$error)){
                    echo '<label for="code" class="error">'.$error['passconf'].'</label>';
                }
                ?>
            </div>
        </div>


      <div class="form-group">
        <div class="col-lg-offset-2 col-lg-4">
          <button type="submit" id="formsubmit" class="btn btn-success update_password">提交信息</button>
        </div>
      </div>
    </form>
  </div>
 </div>
</section>

<?php $this->load->view("{$template_patch}/public/footer.php");?>