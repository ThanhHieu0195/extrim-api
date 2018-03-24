<?php
// Requests from the same server don't have a HTTP_ORIGIN header
require_once './classes/Constants.php';
require_once './classes/MyAPI.php';
require_once './classes/Helper.php';
require_once './classes/DB.php';

if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
    $API = new MyAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    echo $API->processAPI();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}