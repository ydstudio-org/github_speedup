<?php
// error_reporting(0);
$user = $_POST['user'];
$password = md5($_POST['pass']);
$oper = $_POST['oper'];
require_once('../core/database_config.php');
require_once('../core/functions.php');
filterAll($_POST);
$db = new mysqli(HOST,USER,PASSWORD,TABLE);
//初始化数据库链接
$msg = "MSG_NOT_INITIALIZED";
$status = 0;
$cont = "MSG_NOT_INITIALIZED";
$uuid = "000000";
$json_arr = array(
	'msg'=>&$msg,
	'status'=>&$status,
	'cont'=>&$cont,
	'uuid'=>&$uuid
);


function Init()
{
	// echo ('Login Status:' . $_COOKIE["login_status"] . '<br />');
	// SendMessage("登陆成功");
	// war("登陆成功");
}



function Login($user, $password, $db)
{
	global $UA,$json_arr,$msg,$status,$cont,$uuid,$oper;
	$sql = 'SELECT * FROM `users` WHERE `user` = \'' . $user . '\' AND `pwd` = \'' . $password . '\'';
	$res = $db->query($sql);
	$result = mysqli_fetch_array($res);
	$uuid = $result['uuid'];
	$rows = mysqli_affected_rows($db);
	$token = GetToken($password,$result['time'],$UA);


	if ($rows >= 1) {
		setcookie("login_status", 'true', time() + 3600 * 7);
		setcookie('token',$token);
		setcookie('user',$user,time() + 3600 *7);
		setcookie('UA',$UA);
        if ($oper == "GetUserInfo")
        {
            header("content-type: application/json");
            die (GetUserInfo($uuid));
        }
        else
        {
		$msg='凭据认证成功';
		$status='200';
		$cont='已经认证的用户:' . $user;

		echo(json_encode($json_arr));
        }

		$sql = 'UPDATE users SET token = \'' . $token . '\' WHERE user = \'' . $user . '\'';
		$db -> query($sql);
		// echo ('Successful.<br />');
		// echo ('Register Time:' . $result['time']);

	} else if ($rows == -1) {

			$msg='服务器异常';
			$status='500';
			$cont='SQL Syntax Error';

		echo(json_encode($json_arr));
	} else {

			$msg='登录凭据无效 受影响的行数:'  . $rows;
			$status='403';
			$cont='用户名或密码错误';

		echo(json_encode($json_arr));
	}
}

Init();
Login($user, $password, $db);


$db->close();
?>