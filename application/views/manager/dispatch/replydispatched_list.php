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
    <h2><i class="fa fa-bars"></i>我的回复派单
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
                <th>派发任务</th>
                <th>相关文章标题</th>
                <th>相关回复内容</th>
                <th>派发时间</th>
                <th>工人提交时间</th>
                <th>状态</th>
                <th style="width:10%">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($rs)):?>
                <?php foreach($rs as $rs_row):?>
                    <tr>
                        <td><?php echo $rs_row['id']?></td>
                        <td style="width: 10%;"><?php echo $rs_row['name']?></td>
                        <td><?php if($rs_row['operation']==0) echo '评论回复(id)';else echo '评价回复(id)(正负面)' ?>：<?php echo $rs_row['reply_id'] ?></td>
                        <td><?php echo $rs_row['article_title'] ?></td>
                        <td><?php echo $rs_row['online_comment'] ?></td>
                        <td><?php echo date('Y-m-d H:i:s',$rs_row['created']) ?></td>
                        <td><?php if($rs_row['member_commit']==0)echo '--';else echo date('Y-m-d H:i:s',$rs_row['member_commit']); ?></td>
                        <?php switch ($rs_row['task_done']){
                            case 0:echo '<td style="color: #000000">未完成</td>';break;

                            case 1:echo '<td style="color: #17a90c">已完成</td>';break;
                        }
                        ?>
                        <td>
                            <button class="btn btn-white btn-xs btn-margin"  type="button" onclick="postDelete(<?php echo $rs_row['member_id'] ?>,<?php echo $rs_row['reply_id'] ?>,<?php echo $rs_row['operation'] ?>)"  >删除</button>
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
    //提交选择
    function postDelete(membersID,articleID,operation){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url($admin_path.'/dispatcher/dispatched/replydelete');?>",
            dataType: 'json',
            data: {member_id:membersID,
                article_id:articleID,
                operation:operation,
                '<?php echo $token_name; ?>':"<?php echo $hash; ?>"
            },
            dataType: "text",
            cache:false,
            success:
                function(data){
                    if(data=='1'){
                        alert('删除成功');  //as a debugging message.
                        window.location.reload();
                    }else{
                        alert('操作失败，请重试');  //as a debugging message.
                        window.location.reload();
                    }

                }
        });// you have missed this bracket
    }
</script>
<!--==================== 评论模态框======================= -->



<?php $this->load->view("{$template_patch}/public/footer.php");?>
