<?php
require_once("../core/database_config.php");
require_once("../core/functions.php");
header("content-type: application/json");



$mysql = new mysqli(HOST,USER,PASSWORD,TABLE);

filterAll($_POST);
filterAll($_GET);


$msg="MSG_NOT_INITIALIZE";
$code=0;
$arr = array(
	  'msg'=> &$msg,
	  'status'=> &$code
	   );

	$out_trade_no = filter($mysql,$_GET['out_trade_no']);
	$trade_no = filter($mysql,$_GET['trade_no']);
	$trade_status = filter($mysql,$_GET['trade_status']);
	$type = filter($mysql,$_GET['type']);
    $money = filter($mysql,$_GET['money']);

	if($_GET['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
		//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		//如果有做过处理，不执行商户的业务程序
	}
	else {
		$msg = "trade_status=".$_GET['trade_status'];
		die(json_encode($arr));
	}
	
	if ($money != PAY_MONEY)
	{
	   $msg="支付金额不匹配，请联系客服，如用户非法操作款项无法退还！";
	   $code=400;
	   die(json_encode($arr));
	}
	

    $sql = "SELECT * FROM `user_pay` WHERE `uuid`='$out_trade_no' AND `used`='false'";
    $res = $mysql->query($sql);
    $row = mysqli_fetch_array($res);
    
    if ($row[0] < 1){
        $msg="经查询无此订单 $sql";
	    $code=403;
	    die(json_encode($arr));
    }
    
    $appid = $row['appid'];
    if (empty($appid))
    {
        $msg = "没有appid，充值失败";
        $code = 403;
	    die(json_encode($arr));
    }
    
    $sql = "SELECT * FROM `user_info` WHERE `appid` = '$appid'";
    $res = $mysql->query($sql);
    $row = mysqli_fetch_array($res);
    $user_num = $row['num'];
    $num = $user_num + PAY_ADD_NUM;
    $sql = "UPDATE `user_info` SET `num` = '$num' WHERE `appid` = '$appid'";
    
    if ($mysql->query($sql)){
        $sql = "UPDATE `user_info` SET `free` = 'false' WHERE `appid` = '$appid'";
        $mysql->query($sql);
        $sql = "UPDATE `user_pay` SET `used` = 'true' WHERE `user_pay`.`uuid` = '$out_trade_no'";
        $mysql->query($sql);
        $sql = "SELECT * FROM `user_info` WHERE `num` = '$num' AND `appid` = '$appid'";
        $res = $mysql->query($sql);
        if ($res)
        {
            $row = mysqli_fetch_array($res);
        if ($row['num'] == $num)
        {
            $msg="AppID:<b>$appid</b><br />订单完成,充值前数量:" . $user_num . "充值后数量:<b>" . $num . "</b>";
	        $code=200;
        }
        else
        {
            $msg="未知错误:未能充值成功，请联系开发者";
	        $code=201;
        }
        }
        else
        {
            $msg="未知错误:未能充值成功，请联系开发者:" + $sql;
	        $code=202;
        }




	    die(json_encode($arr));
    }


else 
{
	//验证失败
	    $msg="订单异常";
	    $code=403;
	    die(json_encode($arr));
}
?>