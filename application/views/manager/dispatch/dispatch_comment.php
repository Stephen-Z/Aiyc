<?php
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 30/4/2017
 * Time: 11:13 PM
 * view for dispatch system
 */
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<div class="pageheader">
    <h2><i class="fa fa-bars"></i>选择工人
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

<script>var clicked=0;</script>


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
                <th>工人名字</th>
                <th>签到日期</th>
                <th>派发任务</th>
                <th style="width:10%">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($rs)):?>
                <?php foreach($rs as $rs_row):?>
                    <tr>
                        <td><?php echo $rs_row['user_id']?></td>
                        <td style="width: 25%;"><?php echo $rs_row['name']?></td>
                        <td><?php echo $rs_row['login_date']?></td>
                        <td>评论文章(id)：<?php echo $article_id ?></td>
                        <td>
                            <button class="btn btn-white btn-xs btn-margin"  type="button"  data-toggle="modal" data-target="#myModal" >选择</button>
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

<!-- =============评论模态框============== stephen 2017-05-03 -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">新评论</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label id="commentLabel" for="message-text" class="control-label">Message:</label>
                    <textarea class="form-control" id="message-text" rows="7" placeholder="=======> 在此添加评论 <======="></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="postComment()">Save changes</button>
            </div>
        </div>
    </div>
</div>
<script>
    //设置模态框标题
    function setClick(articleID,articleTitle){
        clicked=articleID;
        document.getElementById("commentLabel").innerHTML='标题： '+articleTitle;
    }
    //提交评论
    function postComment(){
        var articleId=clicked;
        var commentContent=document.getElementById('message-text').value;
        document.getElementById('message-text').value='';
        //alert(articleId);
        //alert('lala');

        $.ajax({
            type: "POST",
            url: "<?php echo base_url($admin_path.'/article/Comment/create');?>",
            dataType: 'json',
            data: {articleid:articleId,
                content:commentContent,
                '<?php echo $token_name; ?>':"<?php echo $hash; ?>"
            },
            dataType: "text",
            cache:false,
            success:
                function(data){
                    if(data=='1'){
                        alert('评论成功，等待审核');  //as a debugging message.
                        window.location.href="<?php echo base_url($admin_path.'/article/comment');?>";
                    }else{
                        alert('评论失败');  //as a debugging message.
                        window.location.href="<?php echo base_url($admin_path.'/article/comment');?>";
                    }

                }
        });// you have missed this bracket

    }
</script>
<!--==================== 评论模态框======================= -->



<?php $this->load->view("{$template_patch}/public/footer.php");?>
