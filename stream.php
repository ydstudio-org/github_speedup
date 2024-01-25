<?php
include_once('core/functions.php');
filterAll($_POST);
$filename = $_POST['url'];
$url = $_POST["url"];
//die($url);
$user = $_POST["user"];

$appid = $_POST['appid'];

$key = $_POST['key'];
include_once('verify.php');

function headerHandler($curl, $headerLine) {
    $len = strlen($headerLine);
    // HTTP响应头是以:分隔key和value的
    $split = explode(':', $headerLine, 2);
    if (count($split) > 1) {
        $key = trim($split[0]);
        $value = trim($split[1]);
        // 将响应头的key和value存放在全局变量里
        $GLOBALS['G_HEADER'][$key] = $value;
    }
    return $len;
}


if (filter_var($url, FILTER_VALIDATE_URL) !== false)
{
    header('Content-Type: application/octet-stream');
    header("Accept-Ranges: bytes"); 
    header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 500);
    curl_setopt($ch, CURLOPT_HEADERFUNCTION, "headerHandler"); 
    curl_setopt($ch, CURLOPT_WRITEFUNCTION, function($curl, $data) {

        echo $data;
        return strlen($data);
    });
    
    curl_exec($ch);
    curl_close($ch);
        // var_dump($GLOBALS['G_HEADER']);
    set_logs($filename,$user,1,"stream","");
}
else {
    http_response_code(403);
    die(json_encode(array('msg'=>'Url_Format_Error')));
}
