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
  <h2><i class="fa fa-bars"></i> 内容管理 <span>添加文章</span></h2>
  <div class="breadcrumb-wrapper">
    <ol class="breadcrumb">
      <li><a href="<?php echo $this->config->base_url($admin_path.'/main');?>">管理首页</a></li>
      <li class="active">添加文章</li>
    </ol>
  </div>
</div>

<section class="contentpanel">
    <div class="panel">
        <div class="panel-body">
            <form class="form-horizontal tasi-form form-bordered" name="adminform" id="adminform" method="post" action="<?php echo $this->config->base_url($admin_path);?>/article/listing/create">

                <input type="hidden" class="token" name="<?php echo $token_name;?>" value="<?php echo $hash;?>" />

                <div class="form-group <?php if(array_key_exists('title',$error)): echo 'has-error'; endif; ?>">
                    <label class="col-sm-2 control-label">标题</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" name="title" value="<?php echo @$title; ?>">
                        <?php
                        if(array_key_exists('title',$error)){
                            echo '<label for="code" class="error">'.$error['title'].'</label>';
                        }
                        ?>
                    </div>
                </div>

                <div class="form-group <?php if(array_key_exists('cid',$error)): echo 'has-error'; endif; ?>">
                    <label for="inputEmail1" class="col-sm-2 control-label">所属分类</label>
                    <div class="col-lg-4">
                        <select class="form-control input-lg m-bot15" name="cid">
                            <option value="0">所属分类</option>
                            <?php foreach($rs as $rs_row):?>
                                <option value="<?php echo $rs_row['id']?>"><?php echo $rs_row['name'];?></option>
                                <?php foreach(@$rs_row['child'] as $child):?>
                                    <option value="<?php echo $child['id']?>">└ <?php echo $child['name'];?></option>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </select>
                        <?php
                        if(array_key_exists('cid',$error)){
                            echo '<label for="code" class="error">'.$error['cid'].'</label>';
                        }
                        ?>
                    </div>
                </div>

                <div class="form-group <?php if(array_key_exists('keywords',$error)): echo 'has-error'; endif; ?>">
                    <label class="col-sm-2 control-label">关键词(Keywords)</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="keywords" value="<?php echo @$keywords; ?>">
                        <?php
                        if(array_key_exists('keywords',$error)){
                            echo '<label for="code" class="error">'.$error['keywords'].'</label>';
                        }
                        ?>
                    </div>
                </div>

                <div class="form-group <?php if(array_key_exists('description',$error)): echo 'has-error'; endif; ?>">
                    <label class="col-sm-2 control-label">描述(Description)</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="description" value="<?php echo @$description; ?>">
                        <?php
                        if(array_key_exists('description',$error)){
                            echo '<label for="code" class="error">'.$error['description'].'</label>';
                        }
                        ?>
                    </div>
                </div>

                <div class="form-group <?php if(array_key_exists('content',$error)): echo 'has-error'; endif; ?>">
                    <label for="inputEmail1" class="col-sm-2 control-label">内容</label>
                    <div class="col-lg-8">
                        <textarea rows="30" cols="50" name="content"><?php echo @$content ?></textarea>
                        <script type="text/javascript">CKEDITOR.replace('content');</script>
                        <?php
                        if(array_key_exists('content',$error)){
                            echo '<label for="code" class="error">'.$error['content'].'</label>';
                        }
                        ?>
                    </div>
                </div>


                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-4">
                        <button type="button" id="formsubmit" class="btn btn-success security">提交信息</button>
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
