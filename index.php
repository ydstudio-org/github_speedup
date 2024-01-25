<?php
error_reporting(0);
//调试信息输出等级
ignore_user_abort(true);
//即使用户退出也不结束本次会话，直到文件下载结束才会结束
ini_set('memory_limit', '128M');
//最大内存限制
ini_set('max_execution_time', 600);
//最大运行时间

include('core/set_logs.php');
include('core/database_config.php');
include('core/functions.php');
include('error.php');
include('downloader/vendor/autoload.php');
require_once('core/api.php');


filterAll($_POST);


$url = $_POST["url"];
//die($url);
$user = $_POST["user"];

//$name = $_GET["name"];

$mode = $_POST["mode"];

$appid = $_POST['appid'];

$key = $_POST['key'];



use Curl\Client;
use CurlDownloader\CurlDownloader;

$ip = get_real_ip();



$mysql = new mysqli(HOST, USER, PASSWORD, TABLE);

$sql = "SELECT * FROM `down_num` WHERE `date` = '$tmp_date' AND `ip` LIKE '$ip'";
//查询用户IP下载次数
$result = $mysql->query($sql);


$row = mysqli_fetch_array($result);

//echo("num:" . $row['num'] . "::" . DOWNLOAD_MAX_NUM);
$num = $row['num'];
$sql = "SELECT * FROM `user_info` WHERE `appid` = '$appid';";
$result = $mysql->query($sql);
$row = mysqli_fetch_array($result);

if ($num >= DOWNLOAD_MAX_NUM && $row['free'] == "true") {
    if ($mode == "json") {
        $msg = array(
            'msg' => "免费API调用达到上限，请升级为捐赠用户或等待明日次数重置",
            'status' => "401",
            'appid' => $appid
        );
        header("Content-Type: application/json");
        die(json_encode($msg));
    } else {
        http_response_code(403);
        die($error_page1 . $ip . $error_page2);
    }



}

$sql = "SELECT * FROM `user_info` WHERE `appid` LIKE '$appid' AND `key` LIKE '$key'";
$result = $mysql->query($sql);
$row = mysqli_fetch_array($result);
if ($row[0] >= 1) {
    if ($row['num'] <= $row['any_num']) {
        $msg = array(
            'msg' => "API调用达到上限，请及时充值",
            'status' => "400",
            'appid' => $appid
        );
        header("Content-Type: application/json");
        die(json_encode($msg));
    }
} else {

    $msg = array(
        'msg' => "APPID OR KEY ERROR",
        'status' => "403",
        'appid' => $appid
    );
    header("Content-Type: application/json");
    die(json_encode($msg));
}


if (filter_var($url, FILTER_VALIDATE_URL) !== false) {

    if (!$user or !$url) {

        $msg = array(
            "msg" => "User Or url Not Found.",
            "status" => "403",

        );
        set_logs($url, $user, $status, $size, $md5);
        header("Content-Type: application/json");
        die(json_encode($msg));


    } else {

        $tmp = QueryDownloadFiles($url);
        if ($tmp != false) {
            //die (json_encode($tmp));
            if (!file_exists("./downloads/$filename"))
            {
                return;
            }
            $filename = $tmp['filename'];
            $md5 = $tmp['md5'];
            $size = $tmp['size'];
            $download_link = SITE_URL . "download.php?filename=" . urlencode($filename);
            returnMsg(urlencode($filename), $md5, $size, $download_link, "json", 200);
        }


        $browser = new Client();
        $downloader = new CurlDownloader($browser);

        $response = $downloader->download("$url", function ($filename1) {
            $filename1 = time() . "-" . urldecode($filename1);
            global $filename;
            $filename = $filename1;

            return './downloads/' . $filename1;

        });

        if ($response->status == 200) {

            $size = $response->info->size_download;
            $status = 'true';
            $md5 = md5_file('./downloads/' . $filename);

        } else {
            $status = 'false';
        }


        $size = round(($size / 1024) / 1024, 2);

        $size_m = $size . "MB";
        set_logs($url, $user, $status, $size, $md5, $filename);
        //写入日志
        $sql = "SELECT * FROM `user_info` WHERE `appid` = '$appid';";
        //查询用户信息
        $result = $mysql->query($sql);
        $row = mysqli_fetch_array($result);
        $num = $row['any_num'] + 1;
        $sql = "UPDATE `user_info` SET `any_num` = '$num' WHERE `user_info`.`appid` = '$appid'";
        $mysql->query($sql);
        //更新下载次数
        $mysql->close();
        $download_link =  SITE_URL . "download.php?filename=$filename";
        if ($mode != "json") {
            returnMsg($filename, $md5, $size_m, $download_link, "html", 200);
        } elseif ($mode == "json") {
            returnMsg($filename, $md5, $size_m, $download_link, "json", $response->status);
        }
    }


} else {

    echo 'URL_ERR'. $url;

}


function returnMsg($filename, $md5, $size, $link, $method, $statusCode = 403)
{
    global $url,$user;
    if ($method == "json") {
        if ($statusCode != 200 && $statusCode != 301)
        {
            $msg = "远端服务器无法完成响应";
        }
        else
        {
            $msg = "success";
        }
        header("Content-Type: application/json");
        $json_arr = array(
            'msg' => &$msg,
            'status' => $statusCode,
            'file_name' => "$filename",
            'download_link' => "$link",
            'md5' => "$md5",
            'size' => "$size MB",
            'api_version' => version
        );
        set_logs($url, $user, 1, $size, "FILE_EXISTS", $filename);
        $json = json_encode($json_arr);
        die($json);
    } else {
     
        $content = <<<EOF
                <form style= 'display:none'  name= 'submit_form'  id= 'submit_form'  action= 'success.php'  method= 'post' >
                <input name="key" value="5c7258332d969dbe656487f0fc761ce0">
                <input name="filename" value="$filename">
                <input name="md5" value="$md5">
                <input name="size" value="$size">
                <input name="link" value="$link">
                </form>
                <script type= "text/javascript" >
                document.submit_form.submit();
                </script>
EOF;
        set_logs($url, $user, 1, $size, "FILE_EXISTS", $filename);
        die($content);
    }
}



?>