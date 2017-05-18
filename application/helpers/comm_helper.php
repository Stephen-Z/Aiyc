<?php

function showMessage($status,$message,$id='')
{
    $messagearr = array('status'=>$status,'message'=>$message,'id'=>$id);
    $json = json_encode($messagearr);
    echo $json;
    exit;
}