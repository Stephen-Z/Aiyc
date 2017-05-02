<?php
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<div class="pageheader">
  <h2><i class="fa fa-bars"></i> 百度管理 <span>导入列表</span>
  </h2>
  <div class="breadcrumb-wrapper">
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->config->base_url($admin_path);?>/main/dashboard">管理首页</a></li>
      <li class="active"><a href="<?php echo base_url($admin_path.'/article/baidu');?>">导入列表</a></li>
    </ol>
  </div>
</div>

<section class="contentpanel">
    <form class="form-horizontal tasi-form form-bordered" name="adminform" id="adminform" method="post" action="<?php echo $this->config->base_url($admin_path);?>/article/baidu/excel_create" enctype="multipart/form-data">
        <input type="hidden" class="token" name="<?php echo $token_name;?>" value="<?php echo $hash;?>" />
    <header class="panel-heading clearfix" style="padding: 0px;">
        <form id="filter" method="get">
            <div class="panel-body">

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-3">
                        <div class="form-group">
                        <input class="form-control" type="file" name="file">
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="form-group">
                            <div style="padding-top: 3px">
                                <button type="submit" class="btn btn-info"><i
                                        class="glyphicon glyphicon-search"></i> 导入
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group" style="padding-top: 35px; padding-left: 10px;">
                            <a href="<?php echo base_url('uploads/demo.xlsx')?>">excel格式下载</a>
                        </div>
                    </div>


                </div>
            </div>
        </form>
    </header>
    </form>


    <header class="panel-heading clearfix" style="padding: 0px;">
        <form id="filter" method="get">
            <div class="panel-body">

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group input-group input-group-lg">
                            <span class="input-group-addon">任务名称 </span>
                            <input type="text" class="form-control" name="task" value="<?php echo @$_GET['task']?>">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group input-group input-group-lg">
                            <span class="input-group-addon">平台 </span>
                            <input type="text" class="form-control" name="platform" value="<?php echo @$_GET['platform']?>">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group input-group input-group-lg">
                            <span class="input-group-addon">品牌 </span>
                            <input type="text" class="form-control" name="brand" value="<?php echo @$_GET['brand']?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group input-group input-group-lg">
                            <span class="input-group-addon">标题 </span>
                            <input type="text" class="form-control" name="title" value="<?php echo @$_GET['title']?>">
                        </div>
                    </div>

                </div>

                <div class="row" style="margin-top: 15px;">

                    <div class="col-md-6">
                        <div class="form-group input-group input-group-lg">
                            <span class="input-group-addon">网址URL </span>
                            <input type="text" class="form-control" name="url" value="<?php echo @$_GET['url']?>">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="发布起始时间" id="datepicker_start"
                                       name="startTime">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="发布结束时间" id="datepicker_end"
                                       name="endTime">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="form-group">
                            <div style="padding-top: 3px">
                                <button type="submit" class="btn btn-info"><i
                                        class="glyphicon glyphicon-search"></i> 筛选
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </header>


    <div id="sample_1_wrapper" class="panel-heading dataTables_wrapper form-inline ischeckbox" role="grid" style="overflow-x:scroll;">

      <table class="table border-top table-hover" >
          <thead>
          <tr>
              <th></th>
              <th>ID</th>
              <th>任务名称</th>
              <th>标题</th>
              <th>url</th>
              <th>平台</th>
              <th>品牌</th>
              <th>发布时间</th>
              <th>搜索时间</th>
              <th style="width:10%">操作</th>
          </tr>
          </thead>
          <tbody>
          <?php if(!empty($rs)):?>
              <?php foreach($rs as $rs_row):?>
                  <tr>
                      <td><input type="checkbox" name="ids" value="<?php echo $rs_row['id'] ?>"></td>
                      <td><?php echo $rs_row['id']?></td>
                      <td><?php echo $rs_row['task']?></td>
                      <td><?php echo $rs_row['title']?></td>
                      <td><?php echo $rs_row['url']?></td>
                      <td><?php echo $rs_row['platform']?></td>
                      <td><?php echo $rs_row['brand']?></td>
                      <td><?php echo date('Y-m-d',$rs_row['release_time'])?></td>
                      <td><?php if($rs_row['search_time']!=0){echo date('Y-m-d',$rs_row['search_time']);}?></td>
                      <td>
                          <button class="btn btn-white btn-xs btn-margin category_del" data-toggle="modal" data-target="#myModal" data-id="<?php echo $rs_row['id'];?>" data-url="<?php echo $this->config->base_url($admin_path);?>/article/baidu/excel_delete">删除</button>
                      </td>
                  </tr>
              <?php
              endforeach;
              endif;
              ?>
          </tbody>
      </table>

        <div class="row" style="margin-left: 10px;margin-top: 10px;">
            <div class="btn-group btn-group-md">
                <button id="selectAll" class="btn btn-white btn-xs btn-margin">全选</button>
                <button id="reverse" class="btn btn-white btn-xs btn-margin">返选</button>
                <button id="unSelect" class="btn btn-white btn-xs btn-margin">取消</button>
                <button class="btn btn-orange btn-xs search_button btn-margin" data-toggle="modal" data-target="#myModalSearch"
                        data-url="<?php echo base_url($admin_path.'/article/baidu/go'); ?>">执行搜索
                </button>
                <button class="btn btn-danger btn-xs btn-margin bath_del" data-toggle="modal" data-target="#myModal"
                        data-url="<?php echo base_url($admin_path.'/article/baidu/excel_delete'); ?>">批量删除
                </button>
            </div>
        </div>

      <?php $this->load->view("{$template_patch}/public/page2.php");?>



  </div>
</section>

<!-- Modal -->
<div class="modal fade" id="myModalSearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">请求数据</h4>
            </div>
            <div class="modal-body stxt">确认搜索请求？ </div>
            <div class="modal-footer sbtn">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="search_send">确定</button>
            </div>
        </div>
        <!-- modal-content -->
    </div>
    <!-- modal-dialog -->
</div>
<!-- modal -->

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">删除记录</h4>
            </div>
            <div class="modal-body">删除选中的记录？ </div>
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

<script>

$(function(){

    $(".search_button").click(function(){
        var url = $(this).attr("data-url");
        $("#search_send").attr('data-url',url);
        $("#search_send").attr('ids',delete_ids());
    });

    $("#search_send").click(function(){
        //$("#myModalSearch").modal("hide");
        $('.stxt').html('正在执行收录程序，请等待数秒...等页面自动刷新');
        $('.sbtn').css({"display":"none"});
        var url = $(this).attr("data-url");
        var ids = $(this).attr("ids");
        var data= {'ids': ids.split(","),"<?php echo $token_name;?>":"<?php echo $hash;?>"};
        ajaxsubmit(url,data,'<?php echo $this->config->base_url($admin_path);?>/article/baidu/excel');
    });

    $(".bath_del").click(function(){
        var url = $(this).attr("data-url");
        $("#deletekey").attr('data-url',url);
        $("#deletekey").attr('ids',delete_ids());
    });

    $(".category_del").click(function(){
        var url = $(this).attr("data-url");
        var aid = $(this).attr("data-id");
        $("#deletekey").attr('data-url',url);
        $("#deletekey").attr('ids',aid);
    });


    $("#deletekey").click(function(){
    	$("#myModal").modal("hide");
   	     var url = $(this).attr("data-url");
         var ids = $(this).attr("ids");
         var data= {'ids': ids.split(","),"<?php echo $token_name;?>":"<?php echo $hash;?>"};
         ajaxsubmit(url,data,'<?php echo $this->config->base_url($admin_path);?>/article/baidu/excel');
    });

    $('#datepicker_start').datepicker({
        dateFormat: "yy-mm-dd"
    });
    <?php if (@$startTime): ?>
    $('#datepicker_start').val('<?php echo $startTime;?>');
    <?php endif ?>

    $('#datepicker_end').datepicker({
        dateFormat: "yy-mm-dd"
    });
    <?php if (@$endTime): ?>
    $('#datepicker_end').val('<?php echo $endTime;?>');
    <?php endif ?>

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
