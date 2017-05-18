<?php
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
if(empty($error)) {
    $error = array();
}
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<script src="<?php echo $this->config->base_url($style_path.'/ckeditor/ckeditor.js');?>"></script>
<div class="pageheader">
  <h2><i class="fa fa-bars"></i>用户管理 <span>扣减积分</span></h2>
  <div class="breadcrumb-wrapper">
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->config->base_url($admin_path.'/main');?>">管理首页</a></li>
      <li class="active">扣减积分</li>
    </ol>
  </div>
</div>

<section class="contentpanel">
    <div class="panel">
        <div class="panel-body">
            <form class="form-horizontal tasi-form form-bordered" name="adminform" id="adminform" method="post" action="<?php echo $this->config->base_url($admin_path);?>/member/listing/update">

                <input type="hidden" class="token" name="<?php echo $token_name;?>" value="<?php echo $hash;?>" />
                <input type="hidden" name="id" value="<?php echo $rs['id'];?>" />
                <input type="hidden" name="updated" value="<?php echo $rs['updated'];?>" />

                <div class="form-group">
                    <label class="col-sm-2 control-label">帐号</label>
                    <div class="col-sm-2">
                        <label for="code" class="" style="padding-top: 5px;"><?php echo $rs['account']?></label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">现有积分</label>
                    <div class="col-sm-2">
                        <label for="code" class="error" style="padding-top: 5px;"><?php echo $rs['score']?></label>
                    </div>
                </div>

                <div class="form-group <?php if(array_key_exists('score',$error)): echo 'has-error'; endif; ?>">
                    <label class="col-sm-2 control-label">扣减积分</label>
                    <div class="col-sm-2">
                        <input class="form-control" type="text" name="score" value="<?php echo @$score; ?>">
                        <?php
                        if(array_key_exists('score',$error)){
                            echo '<label for="code" class="error">'.$error['score'].'</label>';
                        }
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-4">
                        <button type="button" id="formsubmit" class="btn btn-success security">提交信息</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</section>

<script>

    $('.security').click(function(){
        $.getJSON("<?php echo $this->config->base_url($admin_path.'/main/security');?>", function(json){
            var token_name=json.data.token_name;
            var hash=json.data.hash;
            $('.token').attr('name',token_name);
            $('.token').val(hash);
            $('#adminform').submit();
        });
    });

</script>

<?php $this->load->view("{$template_patch}/public/footer.php");?>
