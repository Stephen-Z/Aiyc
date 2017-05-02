<?php
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
?>
<!--
 * Created by PhpStorm.
 * User: stephen
 * Date: 5/2/17
 * Time: 11:11 PM
 view for sign in status
 */-->
<?php $this->load->view("{$template_patch}/public/header.php");?>
    <div class="pageheader">
        <h2><i class="fa fa-bars"></i> 签到
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

        <div id="sample_1_wrapper" class="panel-heading dataTables_wrapper form-inline ischeckbox" role="grid">

            <table class="table border-top table-hover" >
                <thead>
                <tr>
                    <th>用户</th>
                    <th>日期</th>
                    <th>状态</th>
                </tr>
                </thead>
                <tbody>
                <?php if(!empty($rs)):?>
                    <?php foreach($rs as $rs_row):?>
                        <tr>
                            <td><?php echo $rs_row['name']?></td>
                            <td><?php echo $rs_row['login_date']?></td>
                            <td>签到成功</td>
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