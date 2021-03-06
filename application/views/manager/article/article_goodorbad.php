<?php
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<script>var CLICKED=0;</script>
<div class="pageheader">
  <h2><i class="fa fa-bars"></i> 文章正负面
      <?php
      if(!empty($cnrs)){
          echo '<span>'.$cnrs['name'].'</span>';
      }
      ?>
  </h2>
  <div class="breadcrumb-wrapper">
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->config->base_url($admin_path);?>/main/dashboard">管理首页</a></li>
      <!-- <li class="active"><a href="<?php echo base_url($admin_path.'/article/listing');?>">全部列表</a></li> -->
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

                    <div class="col-md-4">
                        <div class="form-group input-group input-group-lg">
                            <span class="input-group-addon">搜索</span>
                            <input type="text" class="form-control" name="reply" value="<?php echo @$_GET['search']?>">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <div style="padding-top: 3px">
                                <button type="submit" class="btn btn-info"><i
                                        class="glyphicon glyphicon-search"></i> 搜索
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
              <th>文章标题</th>
              <th>来源网站</th>
              <th>抓取时间</th>
              <th>派发时间</th>
              <th>正负面</th>
              <!-- <th>处理状态</th> -->
              <th style="width:10%">操作</th>
          </tr>
          </thead>
          <tbody>
          <?php if(!empty($rs)):?>
              <?php foreach($rs as $rs_row):?>
                  <tr>
                      <td><?php echo $rs_row['Did']?></td>
                      <td><a target="_blank" href="<?php echo $rs_row['share_url'] ?>"><?php echo $rs_row['title']?></a></td>
                      <td><?php echo $rs_row['author']?></td>
                      <td><?php echo date("Y-m-d H:i:s",$rs_row['created']);?></td>
                      <!-- <td><?php echo $rs_row['pre_reply']?></td> -->
                      <td><?php echo date('Y-m-d H:i:s',$rs_row['Dcreated']) ?></td>

                      <td id="<?php echo 'positive'.strval($rs_row['id']);?>"><?php switch($rs_row['positive']){
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
                      <!-- <td><?php switch($rs_row['status']){
                              case 0:
                                  echo '未处理';
                                  break;
                              case 1:
                                  echo '处理中';
                                  break;
                              case 2:
                                  echo '<span style="color:red">处理完成</span>';
                                  break;
                          }?></td> -->
                      <td>
                          <div class="dropdown">
                              <button id="dLabel" class="btn btn-white btn-xs btn-margin" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  评价
                                  <span class="caret"></span>
                              </button>
                              <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                                  <li><button class="btn btn-white btn-block btn-margin" type="button" onclick="postInfo(<?php echo $rs_row['Did']?>,<?php echo $rs_row['id']?>,1)">正面</button></li>
                                  <li><button class="btn btn-white btn-block btn-margin" type="button" onclick="postInfo(<?php echo $rs_row['Did']?>,<?php echo $rs_row['id']?>,0)">负面</button></li>
                                  <li><button class="btn btn-white btn-block btn-margin" type="button" onclick="postInfo(<?php echo $rs_row['Did']?>,<?php echo $rs_row['id']?>,2)">未处理</button></li>
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

<!--发表评价 stephen 2017-05-06-->
<script>
    var ARTICLEID=-1;
    var STATUS=-1;
    var TASKID=-1;
</script>
<script>
    function postInfo(taskID,articleID,Status){
        ARTICLEID=articleID;
        STATUS=Status;
        TASKID=taskID;
        $.ajax({
            type: "POST",
            async: true,
            url: "<?php echo base_url($admin_path.'/article/Goodorbad/update');?>",
            dataType: 'json',
            data: {articleid:articleID,
                status:Status,
                taskid:TASKID,
                '<?php echo $this->security->get_csrf_token_name()?>':"<?php echo $this->security->get_csrf_hash()?>"
            },
            dataType: "text",
            cache:false,
            success:
                function(data){
                    if(data=='1'){
                        alert('评价成功');
                        window.location.reload();
//                        if(STATUS == 0){document.getElementById('positive'+ARTICLEID).innerHTML='负面';}
//                        else if(STATUS == 1){document.getElementById('positive'+ARTICLEID).innerHTML='正面';}
//                        else if(STATUS == 2){document.getElementById('positive'+ARTICLEID).innerHTML='未处理';}
                    }else{
                        alert('评价失败');
                        window.location.reload();
                    }

                }
        });
    }
</script>
<!--发表评价 stephen 2017-05-06-->

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
