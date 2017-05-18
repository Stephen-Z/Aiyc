<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/


//默认开启SESSION
$hook['pre_system'] = array(
    'class'    => '',
    'function' => 'session_start',
    'filename' => '',
    'filepath' => '',
    'params'   => '',
);