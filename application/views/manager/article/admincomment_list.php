<?php
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 30/4/2017
 * Time: 11:13 PM
 * view for 评论列表
 */
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<div class="pageheader">
    <h2><i class="fa fa-bars"></i> 评论列表
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
                <th>评论内容</th>
                <th>用户ID</th>
<!--                <th>来源网站</th>-->
<!--                <th>抓取时间</th>-->
<!--                <th>派发时间</th>-->
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
                        <td style="width: 25%;"><a href="<?php echo $rs_row['url'] ?>"><?php echo $rs_row['title']?></a></td>
                        <td style="width: 45%;"><?php echo $rs_row['content']?></td>
                        <td><?php echo $rs_row['user_id']?></td>
<!--                        <td>--><?php //echo $rs_row['author']?><!--</td>-->
<!--                        <td>--><?php //echo date("Y-m-d H:i:s",$rs_row['created']);?><!--</td>-->
<!--                        <!-- <td>--><?php //echo $rs_row['pre_reply']?><!--</td> -->
<!--                        <td>???</td>-->
<!--                        <td>???</td>-->
<!--                        <td>--><?php //switch($rs_row['positive']){
//                                case 0:
//                                    echo '负面';
//                                    break;
//                                case 1:
//                                    echo '正面';
//                                    break;
//                                case 2:
//                                    echo '未处理';
//                                    break;
//                            }?><!--</td>-->
                       <td><?php switch($rs_row['task_done']){
                            case 0:
                                echo '<span style="color:#b1b1b1">工人未提交</span>';
                               break;
                            case 1:
                                echo '<span style="color:#000000">工人已提交</span>';
                                break;
                            case 2:
                                echo '<span style="color:#ff0000">未通过审核</span>';
                                break;
                            case 3:
                                echo '<span style="color:#34a03a">已通过</span>';
                              break;
                      }?></td>
                       <td>
                           <div class="dropdown">
                               <button id="dLabel" class="btn btn-white btn-xs btn-margin" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   处理
                                   <span class="caret"></span>
                               </button>
                               <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dLabel">
                                   <li><button class="btn btn-white btn-block btn-margin" type="button" onclick="postInfo(<?php echo $rs_row['Did']?>,<?php echo $rs_row['Aid']?>,<?php echo $rs_row['user_id']?>,3)">通过</button></li>
                                   <li><button class="btn btn-white btn-block btn-margin" type="button" onclick="postInfo(<?php echo $rs_row['Did']?>,<?php echo $rs_row['Aid']?>,<?php echo $rs_row['user_id']?>,2)">不通过</button></li>

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
    function postInfo(taskID,articleID,userID,Status){
        ARTICLEID=articleID;
        STATUS=Status;

        $.ajax({
            type: "POST",
            async: true,
            url: "<?php echo base_url($admin_path.'/article/comment/updatestatus');?>",
            dataType: 'json',
            data: {articleid:articleID,
                status:Status,
                user_id:userID,
                task_id:taskID,
                '<?php echo $this->security->get_csrf_token_name()?>':"<?php echo $this->security->get_csrf_hash()?>"
            },
            dataType: "text",
            cache:false,
            success:
                function(data){
                    if(data=='1'){
                        alert('操作成功');
                        window.location.reload();
                    }else{
                        alert('操作失败');
                        window.location.reload();
                    }

                }
        });
    }
</script>



<?php $this->load->view("{$template_patch}/public/footer.php");?>
