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
                        <td>评价文章(正负面)(id)：<?php echo $article_id ?></td>
                        <td>
                            <button class="btn btn-white btn-xs btn-margin"  type="button" onclick="postSelect(<?php echo $rs_row['user_id'] ?>,<?php echo $article_id ?>)"  >选择</button>
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
    function postSelect(membersID,articleID){
        $.ajax({
            type: "POST",
            url: "<?php echo base_url($admin_path.'/dispatcher/select_member/selected');?>",
            dataType: 'json',
            data: {member_id:membersID,
                article_id:articleID,
                operation:1,
                '<?php echo $token_name; ?>':"<?php echo $hash; ?>"
            },
            dataType: "text",
            cache:false,
            success:
                function(data){
                    if(data=='1'){
                        alert('选择成功');  //as a debugging message.
                        window.location.href="<?php echo $admin_path.'/dispatcher/article_list'?>";
                    }else{
                        alert('操作失败，请重试');  //as a debugging message.
                        window.location.href="<?php echo $admin_path.'/dispatcher/article_list'?>";
                    }

                }
        });// you have missed this bracket
    }
</script>




<?php $this->load->view("{$template_patch}/public/footer.php");?>
