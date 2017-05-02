<?php
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<div class="pageheader">
  <h2><i class="fa fa-bars"></i> 百度收录 <span>收录结果</span>
  </h2>
  <div class="breadcrumb-wrapper">
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->config->base_url($admin_path);?>/main/dashboard">管理首页</a></li>
      <li class="active"><a href="<?php echo base_url($admin_path.'/article/baidu');?>">收录结果</a></li>

    </ol>
  </div>
</div>

<section class="contentpanel">

    <header class="panel-heading clearfix" style="padding: 0px;">
        <form class="export_form" id="filter" method="get">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-1">
                        <div class="form-group">
                            <select class="form-control input-lg m-bot15" name="type">
                                <option <?php if(@$_GET['type']==0){ echo 'selected';}?> value=0>来源</option>
                                <option <?php if(@$_GET['type']==1){ echo 'selected';}?> value="1">新闻</option>
                                <option <?php if(@$_GET['type']==2){ echo 'selected';}?> value="2">网页</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group input-group input-group-lg">
                            <span class="input-group-addon">任务 </span>
                            <input type="text" class="form-control" name="task" value="<?php echo @$_GET['task']?>">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group input-group input-group-lg">
                            <span class="input-group-addon">标题 </span>
                            <input type="text" class="form-control" name="title" value="<?php echo @$_GET['title']?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group input-group input-group-lg">
                            <span class="input-group-addon">网址URL </span>
                            <input type="text" class="form-control" name="url" value="<?php echo @$_GET['url']?>">
                        </div>
                    </div>

                </div>

                <div class="row" style="margin-top:15px;">

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
                                        class="glyphicon glyphicon-search search"></i> 筛选
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="form-group">
                            <div style="padding-top: 3px">
                                <a href="javascript:void (0)" type="submit" class="btn btn-info export"> 导出EXCEL
                                </a>
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
              <th>ID</th>
              <th>任务名称</th>
              <th>标题</th>
              <th>url</th>
              <th>平台</th>
              <th>品牌</th>
              <th>发布时间</th>
              <th>搜索时间</th>
              <th>返回记录数</th>
              <th>收录结果</th>
              <th style="width:10%">操作</th>
          </tr>
          </thead>
          <tbody>
          <?php if(!empty($rs)):?>
              <?php foreach($rs as $rs_row):?>
                  <tr>
                      <td><?php echo $rs_row['id']?></td>
                      <td><?php echo $rs_row['task']?></td>
                      <td><?php echo $rs_row['title']?></td>
                      <td><?php echo $rs_row['url']?></td>
                      <td><?php echo $rs_row['platform']?></td>
                      <td><?php echo $rs_row['brand']?></td>
                      <td><?php echo date("Y-m-d",$rs_row['release_time']);?></td>
                      <!--<td><?php //if($rs_row['type']==1){echo '网页';}else {echo '新闻';}?></td>-->
                      <td><?php echo date("Y-m-d",$rs_row['search_time']);?></td>
                      <td><?php echo $rs_row['record_num']?></td>
                      <td><?php if($rs_row['status']==1){echo '收录';}else {echo '未收录';}?></td>
                      <td>
                          <button class="btn btn-white btn-xs btn-margin category_del" data-toggle="modal" data-target="#myModal" data-id="<?php echo $rs_row['id'];?>" data-url="<?php echo $this->config->base_url($admin_path);?>/article/baidu/delete">删除</button>
                      </td>
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
         ajaxsubmit(url,data,'<?php echo $this->config->base_url($admin_path);?>/article/baidu');
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
});


function delete_ids(){
    var chk_value =[];
    $('input[name=ids]:checked').each(function(){
        chk_value.push($(this).val());
    });
    return chk_value;
}

$('.export').click(function(){
    $('.export_form').attr('action','<?php echo base_url($admin_path.'/article/baidu/export')?>');
    $('.export_form').submit();
});

$('.search').click(function(){
    $('.export_form').attr('action','<?php echo base_url($admin_path.'/article/baidu')?>');
    $('.export_form').submit();
});

</script>

<?php $this->load->view("{$template_patch}/public/footer.php");?>
