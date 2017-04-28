<?php
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<div class="pageheader">
  <h2><i class="fa fa-bars"></i> 会员管理 <span>扣减积分记录</span>
  </h2>
  <div class="breadcrumb-wrapper">
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->config->base_url($admin_path);?>/main/dashboard">管理首页</a></li>
      <li class="active"><a href="<?php echo base_url($admin_path.'/member/listing');?>">会员列表</a></li>
    </ol>
  </div>
</div>

<section class="contentpanel">
  <div id="sample_1_wrapper" class="panel-heading dataTables_wrapper form-inline ischeckbox" role="grid">

      <header class="panel-heading clearfix" >
      </header>

      <table class="table border-top table-hover" >
          <thead>
          <tr>
              <th></th>
              <th>ID</th>
              <th>所属帐号</th>
              <th>联系电话</th>
              <th>扣减积分</th>
              <th>操作时间</th>
          </tr>
          </thead>
          <tbody>
          <?php if(!empty($rs)):?>
              <?php foreach($rs as $rs_row):?>
                  <tr>
                      <td><input type="checkbox" name="ids" value="<?php echo $rs_row['id']?>"></td>
                      <td><?php echo $rs_row['id']?></td>
                      <td><?php echo $rs_row['member']['account']?></td>
                      <td><?php echo $rs_row['member']['phone']?></td>
                      <td><?php echo $rs_row['score']?></td>
                      <td><?php echo date("Y-m-d H:i:s",$rs_row['created']);?></td>
                  </tr>
              <?php
              endforeach;
              endif;
              ?>
          </tbody>
      </table>

      <?php $this->load->view("{$template_patch}/public/page.php");?>

  </div>
</section>


<?php $this->load->view("{$template_patch}/public/footer.php");?>
