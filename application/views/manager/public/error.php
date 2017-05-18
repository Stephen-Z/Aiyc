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
  <h2><i class="fa icon-laptop"></i> <?php if(empty($msg)): echo '服务器繁忙'; else: echo $msg; endif ?> <span></h2>
  <div class="breadcrumb-wrapper">
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->config->base_url();?>admin/dashboard"><?php if(empty($msg)): echo '服务器繁忙'; else: echo $msg; endif ?></a></li>
    </ol>
  </div>
</div>
<section class="contentpanel">
 <div class="panel">
  <div class="panel-body">
    <div class="alert alert-danger" role="alert"><?php if(empty($msg)): echo '服务器繁忙'; else: echo $msg; endif ?></div>
  </div>
 </div>
</section>

    <script language="javascript" type="text/javascript">
        var i = 2;
        var intervalid;
        intervalid = setInterval("fun()", 1000);
        function fun() {
            if (i == 0) {
                window.location.href="<?php echo $this->config->base_url($admin_path.$skip_url);?>";
                clearInterval(intervalid);
            }
            i--;
        }
    </script>

<?php $this->load->view("{$template_patch}/public/footer.php");?>