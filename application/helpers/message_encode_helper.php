<?php


if ( ! function_exists('encode_success_message')) {

    function encode_success_message($result, $type, $total = 0) {
        $json = array();
        $json['code'] = 1;
        $json['message'] = '成功';
        if ($type == 'list') {
            $json['data']['list'] = $result;
        }
        if ($type == 'object') {
            $json['data'] = $result;
        }
        if (is_int($total) && $total >= 0 && $type == 'list') {
            $json['data']['total'] = $total;
        }
        return $json;
    }

}

if ( ! function_exists('encode_insert_message')) {

    function encode_insert_message($id) {
        $json = array();
        $json['code'] = 1;
        $json['message'] = '成功';
        $json['data'] = ['id' => $id];
        return $json;
    }

}

if ( ! function_exists('encode_update_message')) {

    function encode_update_message($result) {
        $json = array();
        $json['code'] = 1;
        $json['message'] = '成功';
        $json['data'] = ['result' => $result];
        return $json;
    }

}

if ( ! function_exists('encode_validate_fail_message')) {

    function encode_validate_fail_message($errors = array()) {
        $json = array();
        $json['code'] = 4;
        $json['message'] = '验证失败';
        $json['data'] = $errors;
        return $json;
    }

}

if ( ! function_exists('encode_exception_message')) {

    function encode_exception_message($exception = '')
    {
        $json = array();
        $json['code'] = 2;
        $json['message'] = $exception;
        return $json;
    }

}

if ( ! function_exists('encode_unauth_message')) {

    function encode_unauth_message() {
        $json = array();
        $json['code'] = 3;
        $json['message'] = '请登录';
        return $json;
    }

}

if ( ! function_exists('encode_openid_fail_message')) {

    function encode_openid_fail_message($errors = array()) {
        $json = array();
        $json['code'] = 5;
        $json['message'] = 'openid不存在';
        $json['data'] = $errors;
        return $json;
    }

}