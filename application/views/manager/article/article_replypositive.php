<?php
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 30/4/2017
 * Time: 11:13 PM
 * view for 文章评论
 */
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<div class="pageheader">
    <h2><i class="fa fa-bars"></i> 回复评价
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
                <th>文章标题</th>
                <th>回复内容</th>
<!--                <th>来源网站</th>-->
<!--                <th>抓取时间</th>-->
                <th>派发时间</th>
                <th>正负面</th>
<!--                <th>是否高危</th>-->
<!--                <th>评论状态</th>-->
                <th style="width:10%">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($rs)):?>
                <?php foreach($rs as $rs_row):?>
                    <tr>
                        <td><?php echo $rs_row['Did']?></td>
                        <td style="width: 45%;"><a target="_blank" href="<?php echo $rs_row['url'] ?>"><?php echo $rs_row['title']?></a></td>
                        <td><?php echo $rs_row['comment_content']?></td>
<!--                        <td>--><?php //echo $rs_row['author']?><!--</td>-->
<!--                        <td>--><?php //echo date("Y-m-d H:i:s",$rs_row['created']);?><!--</td>-->
<!--                        <!-- <td>--><?php //echo $rs_row['pre_reply']?><!--</td> -->
<!--                        <td>???</td>-->
<!--                        <td>???</td>-->
                        <td><?php echo date('Y-m-d H:i:s',$rs_row['Dcreated']) ?></td>
                        <td><?php switch($rs_row['Cpositive']){
                                case -1:
                                    echo '负面';
                                    break;
                                case 1:
                                    echo '正面';
                                    break;
                                case 0:
                                    echo '未处理';
                                    break;
                            }?></td>
<!--                        <td>--><?php //switch($rs_row['comment_status']){
//                            case 0:
//                                echo '<span style="color:#b1b1b1">未评论</span>';
//                                break;
//                            case 1:
//                                echo '<span style="color:#000000">审核中</span>';
//                                break;
//                            case 2:
//                                echo '<span style="color:#ff0000">未通过审核</span>';
//                                break;
//                            case 3:
//                                echo '<span style="color:#34a03a">已评论</span>';
//                                break;
//                        }?><!--</td>-->
                        <td>
                            <div class="dropdown">
                                <button id="dLabel" class="btn btn-white btn-xs btn-margin" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    评价
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                                    <li><button class="btn btn-white btn-block btn-margin" type="button" onclick="postInfo(<?php echo $rs_row['Did']?>,<?php echo $rs_row['order_id']?>,1)">正面</button></li>
                                    <li><button class="btn btn-white btn-block btn-margin" type="button" onclick="postInfo(<?php echo $rs_row['Did']?>,<?php echo $rs_row['order_id']?>,0)">负面</button></li>
                                    <li><button class="btn btn-white btn-block btn-margin" type="button" onclick="postInfo(<?php echo $rs_row['Did']?>,<?php echo $rs_row['order_id']?>,-1)">未处理</button></li>
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
    var ARTICLEID=-1;
    var STATUS=-1;
</script>
<script>
    function postInfo(taskid,articleID,Status){
        ARTICLEID=articleID;
        STATUS=Status;

        $.ajax({
            type: "POST",
            async: true,
            url: "<?php echo base_url($admin_path.'/article/Goodorbad/replyupdate');?>",
            dataType: 'json',
            data: {articleid:articleID,
                status:Status,
                task_id:taskid,
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
                        alert('评价失败');
                        window.location.reload();
                    }
                }
        });
    }
</script>



<?php $this->load->view("{$template_patch}/public/footer.php");?>
