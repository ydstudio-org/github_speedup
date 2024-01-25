<?php
require_once("lib/epay.config.php");
require_once("lib/EpayCore.class.php");
require_once("../core/database_config.php");
require_once("../core/functions.php");
include('../downloader/vendor/autoload.php');




use chillerlan\QRCode\QRCode;
$QRCode = new  QRCode();
$mysql = new mysqli(HOST,USER,PASSWORD,TABLE);

$notify_url = "https://api.service.linxi.info/down_files/pay_system/notify_url.php";
$return_url = "https://api.service.linxi.info/down_files/pay_system/return_url.php";


$out_trade_no = uuid();
//商户网站订单系统中唯一订单号，必填
filterAll($_POST);
filterAll($_GET);


$appid = $_POST['appid'];

//支付方式（可传入alipay,wxpay,qqpay,bank,jdpay）
$type = $_POST['type'];
//商品名称
$name = $_POST['WIDsubject'];
//付款金额
$money = $_POST['WIDtotal_fee'];


$IP = getIP();
$UA=$_SERVER['HTTP_USER_AGENT'];

if (empty($appid))
{
    // AppID 为空
    die("{\"code\":403,\"msg\":\"Appid不能为空\"}");
}



$sql = "SELECT * FROM `user_pay` WHERE `uuid`='$out_trade_no'";
$res = $mysql->query($sql);
if ($res){
    $row = mysqli_fetch_array($res);
}
else
{
    die("{\"code\":500,\"msg\":\"UnKown Error!\"}");
}

if ($row[0] > 0)
{
    die("{\"code\":403,\"msg\":\"订单重复!\"}");
}
$sql = "SELECT * FROM `user_info` WHERE `appid` = '$appid'";
$res = $mysql->query($sql);
$row = mysqli_fetch_array($res);
$timer = time();
if ($row[0] <= 0)
{
    // Appid 不存在
    die("{\"code\":403,\"msg\":\"没有找到这个AppID $sql\"}");
}
$sql = "INSERT INTO `user_pay` (`id`, `time` ,`uuid`, `appid`, `used`,`UA`,`IP`) VALUES (NULL, '$timer' ,'$out_trade_no', '$appid', 'false','$UA','$IP')";
$mysql->query($sql);

/************************************************************/

//构造要请求的参数数组，无需改动
$parameter = array(
	"pid" => $epay_config['pid'],
	"type" => $type,
	"notify_url" => $notify_url,
	"return_url" => $return_url,
	"out_trade_no" => $out_trade_no,
	"name" => $name,
	"money"	=> $money,
	"clientip" => $IP
);

//建立请求
$epay = new EpayCore($epay_config);
$html_text = $epay->apiPay($parameter);
$code = $html_text['code'];
if ($code != 1){
    die($html_text['msg']);
}
$trade_no = $html_text['trade_no'];
$data = $html_text['qrcode'];
$imgdata = $QRCode->render($data);

$ret = array(
    'code' =>'200',
    'id' => $trade_no,
    'sys_id' =>$out_trade_no,
    'qrcode' => $imgdata,
    'money' => $money,
    'IP' => $IP
    );
    header("Content-Type: application/json");
exit(json_encode($ret));

?>