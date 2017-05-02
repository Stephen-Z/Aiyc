<?php
$style_path=REST_Controller::SYSTEM_STYLE_PATH;
$template_patch=REST_Controller::MANAGER_TEMPLATE_PATH;
$admin_path=REST_Controller::MANAGER_PATH;
?>
<?php $this->load->view("{$template_patch}/public/header.php");?>
<div class="pageheader">
  <h2><i class="fa fa-tachometer"></i> 管理首页</h2>
</div>
<section class="contentpanel">

  <div class="panel panel-default">

      <div class="panel-body">

          欢迎进入管理系统后台！

      </div>

  </div>

</section>

<?php $this->load->view("{$template_patch}/public/footer.php");?>

<!-- /*stephen-start*/ -->
<?php $path='system'; ?>
<!-- /*stephen-end*/ -->

<script src="<?php echo $this->config->base_url('public/'.$path);?>/js/chart.js"></script>

<script>
    var randomScalingFactor = function(){ return Math.round(Math.random()*100)};
    var user_date =[];
    var user_data =[];
    var order_date =[];
    var order_data =[];

    <?php
    foreach($userdiagram as $userdiagram_row){
    ?>
    user_date.push('<?php echo $userdiagram_row['date'];?>');
    user_data.push('<?php echo $userdiagram_row['total'];?>');
    <?php
    }
    ?>

    <?php
    foreach($orderdiagram as $orderdiagram_row){
    ?>
    order_date.push('<?php echo $orderdiagram_row['date'];?>');
    order_data.push('<?php echo $orderdiagram_row['total'];?>');
    <?php
    }
    ?>

    var lineChartData = {
        labels : user_date,
        datasets : [
            {
                label: "My First dataset",
                fillColor : "rgba(220,220,220,0.2)",
                strokeColor : "rgba(220,220,220,1)",
                pointColor : "rgba(220,220,220,1)",
                pointStrokeColor : "#fff",
                pointHighlightFill : "#fff",
                pointHighlightStroke : "rgba(220,220,220,1)",
                data : user_data
            },
        ]

    }

    var lineChartDataOrder = {
        labels : order_date,
        datasets : [
            {
                label: "My First dataset",
                fillColor : "rgba(151,187,205,0.2)",
                strokeColor : "rgba(151,187,205,1)",
                pointColor : "rgba(151,187,205,1)",
                pointStrokeColor : "#fff",
                pointHighlightFill : "#fff",
                pointHighlightStroke : "rgba(220,220,220,1)",
                data : order_data
            },
        ]

    }

    window.onload = function(){
        var ctx = document.getElementById("user_canvas").getContext("2d");
        window.myLine = new Chart(ctx).Line(lineChartData, {
            responsive: true
        });

        var ctq = document.getElementById("order_canvas").getContext("2d");
        window.myLine = new Chart(ctq).Line(lineChartDataOrder, {
            responsive: true
        });
    }


</script>
