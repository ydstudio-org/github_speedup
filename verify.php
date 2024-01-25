<?php
include('core/set_logs.php');

include('core/database_config.php');

include('error.php');

include('downloader/vendor/autoload.php');

//include('./get_user_ip.php');

require_once('core/api.php');

use Curl\Client;
use CurlDownloader\CurlDownloader;

$ip = get_real_ip();



$mysql = new mysqli(HOST,USER,PASSWORD,TABLE);

$sql = "SELECT * FROM `down_num` WHERE `date` = '$tmp_date' AND `ip` LIKE '$ip'";
//查询用户IP下载次数
$result = $mysql -> query($sql);


$row = mysqli_fetch_array($result);

//echo("num:" . $row['num'] . "::" . DOWNLOAD_MAX_NUM);
    if ($row['num'] >= DOWNLOAD_MAX_NUM){
        echo($error_page1 . $ip . $error_page2);
        exit;
    }

$sql = "SELECT * FROM `user_info` WHERE `appid` LIKE '$appid' AND `key` LIKE '$key';";
$result = $mysql -> query($sql);
$row = mysqli_fetch_array($result);
if ($row[0] >= 1){
    
}else{
    die(json_encode(array('msg'=>'APPID_OR_KEY_ERR')));
}
$mysql -> close();