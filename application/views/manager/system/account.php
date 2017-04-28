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
  <h2><i class="fa fa-rocket"></i> 管理帐号 <span>管理员列表</span>
  </h2>
  <div class="breadcrumb-wrapper">
    <ol class="breadcrumb">
        <li class="active">管理员列表</li>
    </ol>
  </div>
</div>

<section class="contentpanel">
  <div id="sample_1_wrapper" class="panel-heading dataTables_wrapper form-inline ischeckbox" role="grid">

      <header class="panel-heading clearfix" >
        <span style="float:right">
            <a href="<?php echo $this->config->base_url($admin_path.'/system/add_account');?>">
                <button type="button" class="btn btn-info add_goods"><i class="icon-plus"></i> 添加管理员</button>
            </a>
        </span>
      </header>

      <form method="get" action="<?php echo base_url('manager/gift/to_send')?>">
        <input type="hidden" name="<?php echo @$token_name?>" value="<?php echo @$hash?>">

      <table class="table border-top table-hover" >
          <thead>
          <tr>
              <th>帐号</th>
              <th>手机</th>
              <th>操作</th>
          </tr>
          </thead>
          <tbody>
          <?php if(!empty($rs)):?>
              <?php foreach($rs as $rs_row):?>
                  <tr>
                      <td><?php echo $rs_row['name']?></td>
                      <td><?php echo $rs_row['phone']?></td>
                      <td><a href="<?php echo base_url("manager/system/edit_account/{$rs_row['id']}")?>">修改</a>&nbsp;&nbsp;<a href="javascript:if(confirm('确实要删除吗?'))location='<?php echo base_url("manager/system/del_admin/{$rs_row['id']}")?>'">删除</a> </td>
                  </tr>
              <?php
              endforeach;
              endif;
              ?>
          </tbody>
      </table>

          <?php $this->load->view("{$template_patch}/public/page.php");?>


      </form>
      

  </div>
</section>
<script>

$(function(){

    $("#selectAll").click(function () {//全选
        $(".ischeckbox :checkbox").attr("checked", true);
    });

    $("#unSelect").click(function () {//全不选
        $(".ischeckbox :checkbox").attr("checked", false);
    });

    $("#reverse").click(function () {//反选
        $(".ischeckbox :checkbox").each(function () {
            $(this).attr("checked", !$(this).attr("checked"));
        });
    });
});


function delete_ids(){
    var chk_value =[];
    $('input[name=ids]:checked').each(function(){
        chk_value.push($(this).val());
    });
    return chk_value;
}

</script>

<?php $this->load->view("{$template_patch}/public/footer.php");?>
