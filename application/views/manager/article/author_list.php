<?php
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<div class="pageheader">
  <h2><i class="fa fa-bars"></i> 作者管理 <span>作者列表</span>
      <?php
      if(!empty($cnrs)){
          echo '<span>'.$cnrs['name'].'</span>';
      }
      ?>
  </h2>
  <div class="breadcrumb-wrapper">
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->config->base_url($admin_path);?>/main/dashboard">管理首页</a></li>
      <li class="active"><a href="<?php echo base_url($admin_path.'/article/author');?>">作者列表</a></li>
    </ol>
  </div>
</div>

<section class="contentpanel">
  <div id="sample_1_wrapper" class="panel-heading dataTables_wrapper form-inline ischeckbox" role="grid">

      <table class="table border-top table-hover" >
          <thead>
          <tr>
              <th>作者名称</th>
              <th>文章总数</th>
              <th>品牌数量</th>
              <th>正面数量</th>
              <th>负面数量</th>
              <th style="width:20%">操作</th>
          </tr>
          </thead>
          <tbody>
          <?php if(!empty($rs)):?>
              <?php foreach($rs as $rs_row):?>
                  <tr>
                      <td><?php echo $rs_row['author']?></td>
                      <td><?php echo $rs_row['article_count']?></td>
                      <td><?php echo $rs_row['brand_count']?></td>
                      <td><?php echo $rs_row['positive_up_count']?></td>
                      <td><?php echo $rs_row['positive_down_count']?></td>
                      <td>
                          <button class="btn btn-white btn-xs btn-margin" onclick="javascript:window.location.href='<?php echo base_url($admin_path.'/article/listing/?author='.$rs_row['author'])?>'">查看列表</button>
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
