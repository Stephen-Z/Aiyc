<?php
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<div class="pageheader">
  <h2><i class="fa fa-bars"></i> 文章管理 <span>文章高危</span>
      <?php
      if(!empty($cnrs)){
          echo '<span>'.$cnrs['name'].'</span>';
      }
      ?>
  </h2>
  <div class="breadcrumb-wrapper">
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->config->base_url($admin_path);?>/main/dashboard">管理首页</a></li>
      <li class="active"><a href="<?php echo base_url($admin_path.'/article/listing');?>">全部列表</a></li>
      <?php
      if(!empty($cnrs)){
          echo '<li class="active">'.$cnrs['name'].'</li>';
      }
      ?>
    </ol>
  </div>
</div>

<section class="contentpanel">

    <header class="panel-heading clearfix" style="padding: 0px;">
        <form id="filter" method="get">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control input-lg m-bot15" name="cid">
                                <option value="">品牌</option>
                                <?php foreach($column as $column_row):?>
                                   <option <?php if(@$_GET['cid']==$column_row['id']){ echo 'selected';}?> value="<?php echo $column_row['id'];?>"><?php echo $column_row['name'];?></option>;
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control input-lg m-bot15" name="positive">
                                <?php if(empty($_GET['positive'])){$_GET['positive']=2;}?>
                                <option <?php if(@$_GET['positive']==2){ echo 'selected';}?> value=2>正负面</option>
                                <option <?php if(@$_GET['positive']==1){ echo 'selected';}?> value="1">正面</option>
                                <option <?php if(@$_GET['positive']==0){ echo 'selected';}?> value="0">负面</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <select class="form-control input-lg m-bot15" name="status">
                                <?php
                                    if(!isset($_GET['status'])){$_GET['status']=3;}
                                    if($_GET['status']==''){$_GET['status']=3;}
                                ?>
                                <option <?php if(@$_GET['status']==3){ echo 'selected';}?> value="">处理状态</option>
                                <option <?php if(@$_GET['status']==0){ echo 'selected';}?> value="0">未处理</option>
                                <option <?php if(@$_GET['status']==1){ echo 'selected';}?> value="1">处理中</option>
                                <option <?php if(@$_GET['status']==2){ echo 'selected';}?> value="2">处理完成</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group input-group input-group-lg">
                            <span class="input-group-addon">回复>= </span>
                            <input type="text" class="form-control" name="reply" value="<?php echo @$_GET['reply']?>">
                        </div>
                    </div>

                </div>
                <div class="row" style="margin-top: 20px;">

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


    <div id="sample_1_wrapper" class="panel-heading dataTables_wrapper form-inline ischeckbox" role="grid">

      <table class="table border-top table-hover" >
          <thead>
          <tr>
              <th>ID</th>
              <th>品牌</th>
              <th>文章标题</th>
              <th>作者</th>
              <th>发布时间</th>
              <th>是否高危</th>
              <th>正负面</th>
              <th>处理状态</th>
              <th style="width:10%">操作</th>
          </tr>
          </thead>
          <tbody>
          <?php if(!empty($rs)):?>
              <?php foreach($rs as $rs_row):?>
                  <tr>
                      <td><?php echo $rs_row['id']?></td>
                      <td><a href="<?php echo base_url($admin_path.'/article/listing?cid='.$rs_row['brand_id'])?>">[<?php echo $rs_row['cname']?>]</a></td>
                      <td><a target="_blank" href="<?php echo $rs_row['share_url'] ?>"><?php echo $rs_row['title']?></a></td>
                      <td><?php echo $rs_row['author']?></td>
                      <td><?php echo date("Y-m-d H:i:s",$rs_row['release_time']);?></td>
                      <td><?php switch($rs_row['is_danger']){
                              case 0:
                                  echo '未知';
                                  break;
                              case -1:
                                  echo '否';
                                  break;
                              case 1:
                                  echo '是';
                                  break;
                          }?></td> <!--weather is danger-->
                      <td><?php switch($rs_row['positive']){
                              case 0:
                                  echo '负面';
                                  break;
                              case 1:
                                  echo '正面';
                                  break;
                              case 2:
                                  echo '未处理';
                                  break;
                          }?></td>
                      <td><?php switch($rs_row['status']){
                              case 0:
                                  echo '未处理';
                                  break;
                              case 1:
                                  echo '处理中';
                                  break;
                              case 2:
                                  echo '<span style="color:red">处理完成</span>';
                                  break;
                          }?></td>
                      <td>
                          <div class="dropdown">
                              <button id="dLabel" class="btn btn-white btn-xs btn-margin" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  评价
                                  <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                                  <li><button class="btn btn-white btn-block btn-margin" type="button" onclick="postInfo(<?php echo $rs_row['id']?>,-1)">非高危</button></li>
                                  <li><button class="btn btn-white btn-block btn-margin" type="button" onclick="postInfo(<?php echo $rs_row['id']?>,1)">高危</button></li>
                                  <li><button class="btn btn-white btn-block btn-margin" type="button" onclick="postInfo(<?php echo $rs_row['id']?>,0)">未处理</button></li>
                              </ul>
                          </div>
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

<script>
    function postInfo(articleID,isdanger) {
        $.ajax({
            type: "POST",
            async: true,
            url: "<?php echo base_url($admin_path.'/article/listing/updatedanager');?>",
            dataType: 'json',
            data: {articleid:articleID,
                isdanger:isdanger,
                '<?php echo $this->security->get_csrf_token_name()?>':"<?php echo $this->security->get_csrf_hash()?>"
            },
            dataType: "text",
            cache:false,
            success:
                function(data){
                    if(data=='1'){
                        alert('评价成功');
                        window.location.reload();
                    }else{
                        alert('评价失败,请重试或联系技术人员');
                        window.location.reload();
                    }
                }
        });
    }
</script>

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
         ajaxsubmit(url,data,'<?php echo $this->config->base_url($admin_path);?>/article/listing');
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

</script>

<?php $this->load->view("{$template_patch}/public/footer.php");?>
