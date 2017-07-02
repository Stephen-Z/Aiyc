<?php
$path=uri_string();
//$path=current_url();

if(!empty($page_parameter)){
    $str='&'.$page_parameter;
    $str_home='?'.$page_parameter;
}else{
    $str=null;
    $str_home=null;
}

//每页显示多久条数据,显示数目需要与后台匹配一致
$pageNum=10;
//显示分页的数字的数量,请填奇数，如填偶数会自动+1；
$pageTake=5;

if((abs($pageTake)+2)%2==1){
    $pageTake=$pageTake;
}else{
    $pageTake=$pageTake+1;
}
$fc=($pageTake-1)/2;

if(empty($page_total)){
    $page_total=0;
}

$d_count=ceil($page_total/$pageNum);

$lc=$d_count-($pageTake-1);


if(empty($_REQUEST['page'])){
    $current_page=1;
}else {
    $current_page = $_REQUEST['page'];
}

if($pageTake>$d_count){
    $counts=$d_count;
    $c=1;

}else{
    $counts=$pageTake;

    if($current_page>=$counts){
        $c=$current_page-$fc;
    }elseif($pageTake==$current_page){
        $c=$fc;
    }
    else{
        $c=1;
    }
    if($d_count-$fc<$current_page){
        $c=$lc;
    }
}

//上一页
$prev_num=($current_page-2)*$pageNum;
$prev='?page='.($current_page-1).'&skipnum='.$prev_num.'&length='.$pageNum;

//下一页
$next_num=($current_page)*$pageNum;
$netx='?page='.($current_page+1).'&skipnum='.$next_num.'&length='.$pageNum;

//最后一页
$last_page=($d_count-1)*$pageNum;
?>
<div class="page">
    <div class="dataTables_info" id="table2_info">页数：<?php echo '<b>'.$current_page.' / '.$d_count.'</b>'?> &nbsp;总记:<b><?php echo $page_total?></b>条</div>
    <div class="dataTables_paginate paging_full_numbers" id="table2_paginate">
        <a <?php if($current_page>1):?>href="<?php echo $this->config->base_url($path).$str_home?>"<?php endif;?> tabindex="0" class="first paginate_button <?php if($current_page==1): echo 'paginate_button_disabled'; endif;?>" id="table2_first">首页</a>
        <a <?php if($current_page>1):?>href="<?php echo $this->config->base_url($path).$prev.$str?>"<?php endif;?> tabindex="0" class="previous paginate_button <?php if($current_page==1): echo 'paginate_button_disabled'; endif;?>" id="table2_previous">上一页</a>
            <span>
                <?php
                for($i=$c;$i<$c+$counts;$i++){
                    $skipnum=($i-1)*$pageNum;
                    $length=$pageNum;
                ?>
                <a href="<?php echo $this->config->base_url($path)."?page={$i}&skipnum={$skipnum}&length={$length}".$str?>" tabindex="0"  <?php if($current_page==$i):?>class="paginate_active"<?php else :?>class="paginate_button"<?php endif;?>><?php echo $i?></a>
                <?php } ?>
            </span>
        <a <?php if($current_page<$d_count):?>href="<?php echo $this->config->base_url($path).$netx.$str?>"<?php endif;?> tabindex="0"  class="next paginate_button <?php if($current_page==$d_count): echo 'paginate_button_disabled'; endif;?>" id="table2_next">下一页</a>
        <a <?php if($current_page<$d_count):?>href="<?php echo $this->config->base_url($path)."?page={$d_count}&skipnum={$last_page}&length={$length}".$str;?>"<?php endif;?> tabindex="0" class="last paginate_button <?php if($current_page==$d_count): echo 'paginate_button_disabled'; endif;?>" id="table2_last">最后一页</a>
    </div>
</div>