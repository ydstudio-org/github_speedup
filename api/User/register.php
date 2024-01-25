<?php
// error_reporting(E_ALL || ~E_NOTICE); 

require_once('../core/database_config.php');
require_once('../core/functions.php');
require __DIR__ . '/vendor/autoload.php';

header("content-type: application/json");

use Curl\Curl;
use EmailChecker\EmailChecker;

$email = $_POST['email'];
$user = $_POST['user'];
$password = md5($_POST['pass']);
$verifyCode = $_POST['code'];
$opertion = $_POST['oper'];
$checker = new EmailChecker();


$db = new mysqli(HOST,USER,PASSWORD,TABLE);


$msg = '消息未进行初始化';
$statusCode = '000';
$cont = 'Msg_Not_Initialize';
$jsonMessage = array(
    'msg' => &$msg,
    'status' => &$statusCode,
    'cont' => &$cont
);
//初始化JSON消息体

function VerifyRegister($verifyCode)
{
    global $email, $db, $user, $password,$UA,$checker,$jsonMessage,$msg,$statusCode,$cont;
    //引入全局变量

    
    if ($email == null||$verifyCode == null)
    {
        $msg = '注册失败';
        $statusCode = '500';
        $cont = '参数不完整';
        die(json_encode($jsonMessage));
    }
    else if (!$checker->isValid($email))
    {
        $msg = '注册失败';
        $statusCode = '403';
        $cont  = '非法邮箱';
        die(json_encode($jsonMessage));

    }
    else
    {    
        
        $time = time();
        $sql = "SELECT * FROM `register_tmp` WHERE overdue > $time AND email= '$email' AND `status`= '0'";
        $res = $db->query($sql);

        if (!$res)
        {
            $msg = '数据库错误';
            $statusCode = '444';
            $cont = '系统错误,出错的SQL查询:' . $sql;
            die($jsonMessage);
        }
        $row = $res->fetch_array();
    
        if ($db->affected_rows < 1)
        {
            
            $msg = '注册失败';
            $statusCode = '220';
            $cont = '数据异常,受影响的行数:' . $db->affected_rows . 'A:' . $row['code'] . ' B:' . $verifyCode . '  SQL:' . $sql . ' Time:' . time();
            die(json_encode($jsonMessage));
            
        }

        if ($row['code'] == $verifyCode) {
                $e = $row['email'];
                $u = $row['user'];
                $p = $row['password'];
                $t = date('Y-m-d H:i:s',time());
                $token = GetToken($p,strtotime($t),$UA);
                $uuid = uuid();
                $sql = "INSERT INTO `users` (`id`, `email`, `user`, `pwd`, `token`, `status`, `time`, `uuid`) VALUES (NULL, '$e', '$u', '$p', '$token', '0', '$t', '$uuid')";
                
                if ($db->query($sql) && $db->affected_rows > 0)
                {
                    $sql = "UPDATE `register_tmp` SET `status` = '1' WHERE `email` = '$e' AND `code` = '$verifyCode'";
                    $db -> query($sql);
                    if ($db -> affected_rows < 1)
                    {
                        $msg = '注册成功,但是出现了一些问题';
                        $statusCode = '201';
                        $cont = $sql;
                        die(json_encode($jsonMessage));
                    }
                    else
                    {
                        if (CreateUserID($u,$uuid))
                        {
                            $msg = '注册成功';
                            $statusCode = '200';
                            $cont = 'Successful.';
                            die(json_encode($jsonMessage));
                        }
                        else
                        {
                            $msg = '注册成功,但是无法创建与其相关联的APPID';
                            $statusCode = '200';
                            $cont = 'Successful.';
                            die(json_encode($jsonMessage));
                        }

                    }

                }
                else
                {
                    $msg = '注册失败';
                    $statusCode = '403';
                    $cont = $sql;
                    die(json_encode($jsonMessage));
                }
                
        }
        else
        {
            $msg =  '注册失败';
            $statusCode = '601';
            $cont = '请检查你的验证码';
            die(json_encode($jsonMessage));

        }
    }


}


function CheckReg($sendMessage = true)
{
	global $email,$user,$db,$arr,$msg,$statusCode,$cont,$jsonMessage;;

	$sql = "SELECT user,email FROM `users` WHERE `email` = '$email' OR `user` = '$user'";
	$res = $db->query($sql);
	$rows = $db->affected_rows;
	if (!$res)
	{
		$msg = '数据库查询失败';
		$statusCode = '500';
		$cont = $sql;
		die(json_encode($jsonMessage));
	}
	else
	{
		if ($rows > 0)
		{
			$msg = '注册失败';
			$statusCode = '403';
			$cont  = '邮箱或用户名被占用，如已注册请直接登录';
			die(json_encode($jsonMessage));
		}
		else	
		{

                SendVerifyCode();

		}
		
	}
	
}

function CheckTempData($email)
{
    global $db,$arr,$msg,$statusCode,$cont,$jsonMessage;
    $time = time() - 300;
    $sql = "SELECT * FROM `register_tmp` WHERE `email` = '$email' AND `overdue` > $time AND `status` = 0";
	$res = $db->query($sql);
	$rows = $db->affected_rows;
    if (!$res)
    {
        $msg = '数据库查询失败';
        $statusCode = '500';
        $cont = $sql;
        die(json_encode($jsonMessage));
    }
    return $rows . $sql . time();
}


function SendVerifyCode()
{
    global $email, $db, $user, $password,$arr,$msg,$statusCode,$cont,$jsonMessage;
    if (CheckTempData($email) >= 1)
    {
        $msg = '数据校验失败';
        $statusCode = '305';
        $cont = '此邮箱正在进行注册，因此无法提交数据，请等待300秒后重试 Data:' . CheckTempData($email);
        die(json_encode($jsonMessage));
    }
    $randStr = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
    $rand = substr($randStr, 0, 6);
    $time = time() + 300;
    $sql = "INSERT INTO `register_tmp` (`id`, `email`, `user`, `password`, `code`, `overdue`) VALUES (NULL, '$email', '$user', '$password', '$rand', '$time')";
    if (!$db->query($sql))
    {
        $msg = 'Query Failed:';
        $statusCode = '466';
        $cont = '创建用户请求失败,请联系系统管理员';
        die(json_encode($jsonMessage));
    }
    $curl = new Curl();
    $curl->post('http://api.service.linxi.info/mail/email.php', [
        'token' => EMAIL_AUTH_CODE,
        'titl' => '验证码',
        'object' => $email,
        'cont' => "<h2>感谢注册，你的验证码是:$rand</h2><br /><h1>填写时注意大小写哦~</h1>"
    ]);
    if ($curl->error) {
        $msg = 'System Error';
        $statusCode = '444';
        $cont = '发送邮件请求失败,请联系系统管理员';
        die(json_encode($jsonMessage));
    } 
    else 
    {
        
        $json = $curl->response;
        $json = json_decode($json,true);

        if ($json['status'] == '200')
        {
            $msg = '发送成功';
            $statusCode = '200';
            $cont = '电子邮件已经发送到您的邮箱';
            die(json_encode($jsonMessage));
        }
        else
        {
            $msg = '发送失败';
            $statusCode = '200';
            $cont = $json->msg;
            die(json_encode($jsonMessage));
        }
    }

}

if ($opertion == "register") {
	CheckReg();
    

} else {
    VerifyRegister($verifyCode);
}




// if ($curl->error) {
//     echo 'Error: ' . $curl->errorMessage . "\n";
//     $curl->diagnose();
// } else {
//     echo 'Response:' . "\n";
//     var_dump($curl->response);
// }