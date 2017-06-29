<?php
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<div class="pageheader">
  <h2><i class="fa fa-indent"></i> 品牌管理 <span>品牌列表</span></h2>
  <div class="breadcrumb-wrapper">
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->config->base_url($admin_path.'/main');?>">管理首页</a></li>
      <li class="active">品牌列表</li>
    </ol>
  </div>
</div>

<section class="contentpanel">
  <div id="sample_1_wrapper" class="panel-heading dataTables_wrapper form-inline ischeckbox" role="grid">

      <header class="panel-heading clearfix" >
        <span style="float:right">
            <a href="<?php echo $this->config->base_url($admin_path.'/article/column/add');?>">
                <button type="button" class="btn btn-info add_goods"><i class="icon-plus"></i> 添加品牌</button>
            </a>
        </span>
      </header>

      <table class="table border-top table-hover" >
          <thead>
          <tr>
              <th></th>
              <th>ID</th>
              <th>品牌名称</th>
              <th>创建时间</th>
              <th style="width:30%">操作</th>
          </tr>
          </thead>
          <tbody>
          <?php if(!empty($rs)):?>
              <?php foreach($rs as $rs_row):?>
                  <tr>
                      <td><input type="checkbox" name="ids" value="<?php echo $rs_row['id']?>"></td>
                      <td><?php echo $rs_row['id']?></td>
                      <td><strong><a href="<?php echo base_url($admin_path.'/article/listing?cid='.$rs_row['id'])?>"><?php echo $rs_row['name']?></a></strong></td>
                      <td><?php echo date("Y-m-d H:i:s",$rs_row['created']);?></td>
                      <td>
                          <button class="btn btn-white btn-xs btn-margin search_send" data-toggle="modal" data-target="#myModalSearch" data-url="http://120.27.214.29:8099/toutiaoApiDB?keyword=<?php echo $rs_row['name']?>&count=2000&offset=0">爬取执行</button>
                          <button class="btn btn-white btn-xs btn-margin" onclick="javascript:window.location.href='<?php echo base_url($admin_path.'/article/column/edit?id='.$rs_row['id'])?>'">编辑</button>
                          <button class="btn btn-white btn-xs btn-margin category_del" data-toggle="modal" data-target="#myModal" data-id="<?php echo $rs_row['id'];?>" data-url="<?php echo $this->config->base_url($admin_path);?>/article/column/delete">删除</button>
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
          <button class="btn btn-danger btn-xs deleted btn-margin" data-toggle="modal" data-target="#myModal" data-url="<?php echo $this->config->base_url($admin_path);?>/goods/exchange/delete">删除</button>
      </div>


     <!-- Modal -->
     <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title" id="myModalLabel">删除分类</h4>
          </div>
          <div class="modal-body">删除选中的分类？ </div>
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

      <!-- Modal -->
      <div class="modal fade" id="myModalSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h4 class="modal-title" id="myModalLabel">确认爬取？</h4>
                  </div>
                  <div class="modal-body">确认爬取？ </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                      <button type="button" class="btn btn-primary" id="search_send">确定</button>
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

    $(".search_send").click(function(){
        var url = $(this).attr("data-url");
        $("#search_send").attr('data-url',url);
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
         ajaxsubmit(url,data,'<?php echo $this->config->base_url($admin_path);?>/article/column');
    });

    $("#search_send").click(function(){
        $("#myModalSearch").modal("hide");
        var url = $(this).attr("data-url");
        var data= {};
        ajaxsubmit2(url,data,'<?php echo $this->config->base_url($admin_path);?>/article/column');
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
