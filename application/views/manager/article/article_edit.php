<?php
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
if(empty($error)) {
    $error = array();
}
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<script src="<?php echo $this->config->base_url($style_path.'/ckeditor/ckeditor.js');?>"></script>
<div class="pageheader">
  <h2><i class="fa fa-bars"></i> 内容管理 <span>文章详情</span></h2>
  <div class="breadcrumb-wrapper">
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->config->base_url($admin_path.'/main');?>">管理首页</a></li>
      <li class="active">文章详情</li>
    </ol>
  </div>
</div>

<section class="contentpanel">
    <div class="panel">
        <div class="panel-body">
            <form class="form-horizontal tasi-form form-bordered" name="adminform" id="adminform" method="post" action="<?php echo $this->config->base_url($admin_path);?>/article/listing/update">

                <input type="hidden" class="token" name="<?php echo $token_name;?>" value="<?php echo $hash;?>" />
                <input type="hidden" name="updated" value="<?php echo @$info['updated'];?>" />
                <input type="hidden" name="id" value="<?php echo @$info['id'];?>" />

                <div class="form-group">
                    <label class="col-sm-2 control-label">标题</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="" value="<?php echo @$info['title']; ?>" readonly>

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">作者</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" name="" value="<?php echo @$info['author']; ?>" readonly>

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">摘要</label>
                    <div class="col-sm-8">
                        <textarea class="form-control" readonly><?php echo @$info['abstract']; ?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">原文URL</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="" value="<?php echo @$info['url']; ?>" readonly>

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">回复数</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" name="" value="<?php echo @$info['reply']; ?>" readonly>

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">上一次回复</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" name="" value="<?php echo @$info['pre_reply']; ?>" readonly>

                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">发布时间</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" name="" value="<?php echo date("Y-m-d H:i:s",$info['release_time']); ?>" readonly>

                    </div>
                </div>


                <div class="form-group">
                    <label for="inputEmail1" class="col-sm-2 control-label">所属品牌</label>
                    <div class="col-lg-4">
                        <select class="form-control input-lg m-bot15" name="brand_id">
                            <option value="0">未分类</option>
                            <?php foreach($rs as $rs_row):?>
                                <option value="<?php echo $rs_row['id']?>" <?php if($rs_row['id']==$info['brand_id']){ echo 'selected';}?>><?php echo $rs_row['name'];?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail1" class="col-sm-2 control-label">正负面</label>
                    <div class="col-lg-4">
                        <select class="form-control input-lg m-bot15" name="positive">
                            <option <?php if($info['positive']==2){echo 'selected';}?> value="2">无</option>
                            <option <?php if($info['positive']==1){echo 'selected';}?> value="1">正面</option>
                            <option <?php if($info['positive']==0){echo 'selected';}?> value="0">负面</option>
                        </select>

                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail1" class="col-sm-2 control-label">处理状态</label>
                    <div class="col-lg-4">
                        <select class="form-control input-lg m-bot15" name="status">
                            <option <?php if($info['status']==0){echo 'selected';}?> value="0">未处理</option>
                            <option <?php if($info['status']==1){echo 'selected';}?> value="1">处理中</option>
                            <option <?php if($info['status']==2){echo 'selected';}?> value="2">处理完成</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-4">
                        <button type="button" id="formsubmit" class="btn btn-success security">更新</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

</section>

<script>

    $('.security').click(function(){
        $.getJSON("<?php echo $this->config->base_url($admin_path.'/main/security');?>", function(json){
            var token_name=json.data.token_name;
            var hash=json.data.hash;
            $('.token').attr('name',token_name);
            $('.token').val(hash);
            $('#adminform').submit();
        });
    });

</script>

<?php $this->load->view("{$template_patch}/public/footer.php");?>
