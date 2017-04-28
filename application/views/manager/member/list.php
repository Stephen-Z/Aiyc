<?php
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<div class="pageheader">
  <h2><i class="fa fa-bars"></i> 内容管理 <span>会员列表</span>
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
              <th>帐号</th>
              <th>手机</th>
              <th>唯一识别码</th>
              <th>累计积分</th>
              <th>现有积分</th>
              <th>注册时间</th>
              <th style="width:30%">操作</th>
          </tr>
          </thead>
          <tbody>
          <?php if(!empty($rs)):?>
              <?php foreach($rs as $rs_row):?>
                  <tr>
                      <td><input type="checkbox" name="ids" value="<?php echo $rs_row['id']?>"></td>
                      <td><?php echo $rs_row['id']?></td>
                      <td><?php echo $rs_row['account']?></td>
                      <td><?php echo $rs_row['phone']?></td>
                      <td><?php echo $rs_row['identify']?></td>
                      <td><?php echo $rs_row['score_total']?></td>
                      <td><?php echo $rs_row['score']?></td>
                      <td><?php echo date("Y-m-d H:i:s",$rs_row['created']);?></td>
                      <td>
                          <button class="btn btn-white btn-xs btn-margin" onclick="window.location.href='<?php echo base_url($admin_path."/member/listing/edit/".$rs_row['id']);?>'">扣减积分</button>
                          <button class="btn btn-white btn-xs btn-margin category_del" data-toggle="modal" data-target="#myModal" data-id="<?php echo $rs_row['id'];?>" data-url="<?php echo $this->config->base_url($admin_path);?>/member/listing/delete">删除</button>
                      </td>
                  </tr>
              <?php
              endforeach;
              endif;
              ?>
          </tbody>
      </table>

      <?php $this->load->view("{$template_patch}/public/page.php");?>

      <div class="select_btn">
          <button id="selectAll" class="btn btn-white btn-xs btn-margin">全选</button>
          <button id="reverse" class="btn btn-white btn-xs btn-margin">返选</button>
          <button id="unSelect" class="btn btn-white btn-xs btn-margin">取消</button>
          <button class="btn btn-danger btn-xs deleted btn-margin" data-toggle="modal" data-target="#myModal" data-url="<?php echo $this->config->base_url($admin_path);?>/member/listing/delete">删除</button>
      </div>

     <!-- Modal -->
     <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">删除用户</h4>
          </div>
          <div class="modal-body">删除选中的用户？ </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
            <button type="button" class="btn btn-primary" id="deletekey">确定</button>
          </div>
        </div>
        <!-- modal-content -->
      </div>
      <!-- modal-dialog -->
     </div>
     <!-- modal -->

  </div>
</section>
<script>

$(function(){

    $(".deleted").click(function(){
        var url = $(this).attr("data-url");
        $("#deletekey").attr('data-url',url);
        $("#deletekey").attr('ids',delete_ids());
    });

    $(".category_del").click(function(){
        var url = $(this).attr("data-url");
        var ids = $(this).attr("data-id");
        $("#deletekey").attr('data-url',url);
        $("#deletekey").attr('ids',ids);
    });


    $("#deletekey").click(function(){
    	$("#myModal").modal("hide");
   	     var url = $(this).attr("data-url");
         var ids = $(this).attr("ids");
         var data= {'ids': ids.split(","),"<?php echo $token_name;?>":"<?php echo $hash;?>"};
         ajaxsubmit(url,data,'<?php echo $this->config->base_url($admin_path);?>/member/listing');
    });

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
