<?php
/**
 * Created by PhpStorm.
 * User: stephen
 * Date: 30/4/2017
 * Time: 11:13 PM
 * view for 评论正负面
 */
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<div class="pageheader">
    <h2><i class="fa fa-bars"></i> <?php if($child_nav=='comment_reply')echo '评论回复';else echo '评论正负面' ?>
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
                <th>内容</th>
                <th>文章标题</th>
                <th>来源网站</th>
                <th>抓取时间</th>
                <th>派发时间</th>
                <th>是否高危</th>
                <th>正负面</th>
                <?php if($child_nav=='comment_reply')echo '<th>评论状态</th>' ?>
                <th style="width:10%">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(!empty($rs)):?>
                <?php foreach($rs as $rs_row):?>
                    <tr>
                        <td><?php echo $rs_row['id']?></td>
                        <td><?php echo $rs_row['title']?></td>
                        <td><?php echo $rs_row['author']?></td>
                        <td><?php echo date("Y-m-d H:i:s",$rs_row['created']);?></td>
                        <!-- <td><?php echo $rs_row['pre_reply']?></td> -->
                        <td>???<!--派发时间--></td>
                        <td>???<!--是否高危--></td>
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
                        <!-- <td><?php switch($rs_row['status']){
                            case 0:
                                echo '未处理';
                                break;
                            case 1:
                                echo '处理中';
                                break;
                            case 2:
                                echo '<span style="color:#ff0000">处理完成</span>';
                                break;
                        }?></td> -->
                        <td>
                            <button class="btn btn-white btn-xs btn-margin" onclick="javascript:window.location.href=''">评价</button>
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
