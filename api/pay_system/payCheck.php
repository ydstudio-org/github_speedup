<?php
require_once("lib/epay.config.php");
require_once("../core/database_config.php");
require_once("../core/functions.php");
header("Content-Type: application/json");

$mysql = new mysqli(HOST,USER,PASSWORD,TABLE);


if (!$_GET['id'] || is_null($_GET['id'])){
    exit("{\"code\":404,\"msg\":\"API not Found\"}");
}
filterAll($_GET);
$mysql->close();
$trade_no = $_GET['id'];

$html_text = queryOrder($trade_no);
if($html_text['status']==1){
    exit("{\"code\":200,\"status\":0}");
}else{
    exit("{\"code\":200,\"status\":-1}");
}

function queryOrder($trade_no){
    global $epay_config;
    global $trade_no;
    $api_url = $epay_config['apiurl'].'api.php';
	$url = $api_url.'?act=order&pid=' .$epay_config['pid'] . '&key=' . $epay_config['key'] . '&trade_no=' . $trade_no;
	$response = getHttpResponse($url);
	$arr = json_decode($response, true);
	return $arr;
}
	// 请求外部资源
function getHttpResponse($url, $post = false, $timeout = 10){
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		$httpheader[] = "Accept: */*";
		$httpheader[] = "Accept-Language: zh-CN,zh;q=0.8";
		$httpheader[] = "Connection: close";
		curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if($post){
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
}