<?php

if ( ! function_exists('init_page_params')) {

    function init_page_params(&$skipnum, &$length) {
        if ($skipnum == false || intval($skipnum) == 0) {
            $skipnum = 0;
        }
        if ($length == false || intval($length) == 0) {
            $length = 10;
        }
    }

    function init_page_params2(&$skipnum, &$length) {
        if ($skipnum == false || intval($skipnum) == 0) {
            $skipnum = 0;
        }
        if ($length == false || intval($length) == 0) {
            $length = 100;
        }
    }

}