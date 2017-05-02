<?php
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
if(empty($error)) {
    $error = array();
}
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<div class="pageheader">
  <h2><i class="fa fa-indent"></i> 品牌管理 <span>添加品牌</span></h2>
  <div class="breadcrumb-wrapper">
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->config->base_url($admin_path.'/main');?>">管理首页</a></li>
      <li class="active">添加品牌</li>
    </ol>
  </div>
</div>

<section class="contentpanel">
    <div class="panel">
        <div class="panel-body">
            <form class="form-horizontal tasi-form form-bordered" name="adminform" id="adminform" method="post" action="<?php echo $this->config->base_url($admin_path);?>/article/column/create">

                <input type="hidden" class="token" name="<?php echo $token_name;?>" value="<?php echo $hash;?>" />

                <div class="form-group <?php if(array_key_exists('name',$error)): echo 'has-error'; endif; ?>">
                    <label class="col-sm-2 control-label">品牌名称</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" name="name" value="<?php echo @$name; ?>">
                        <?php
                        if(array_key_exists('name',$error)){
                            echo '<label for="code" class="error">'.$error['name'].'</label>';
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
