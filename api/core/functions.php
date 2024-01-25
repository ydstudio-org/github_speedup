<?php
require_once("database_config.php");
$UA = $_SERVER['HTTP_USER_AGENT'];



$db = new mysqli(HOST,USER,PASSWORD,TABLE);


function filter($db,$str)
{
    if (empty($str)) return false;
    $str = mysqli_real_escape_string($db,$str);
    $str = htmlspecialchars($str);
    $str = str_replace( '"', "", $str);
    $str = str_replace( '(', "", $str);
    $str = str_replace( ')', "", $str);
    $str = str_replace( 'CR', "", $str);
    $str = str_replace( 'ASCII', "", $str);
    $str = str_replace( 'ASCII 0x0d', "", $str);
    $str = str_replace( 'LF', "", $str);
    $str = str_replace( 'ASCII 0x0a', "", $str);
    // $str = str_replace( ',', "", $str);
    // $str = str_replace( '%', "", $str);
    // $str = str_replace( ';', "", $str);
    // $str = str_replace( 'eval', "", $str);
    // $str = str_replace( 'open', "", $str);
    // $str = str_replace( 'sysopen', "", $str);
    // $str = str_replace( 'system', "", $str);
    // $str = str_replace( '$', "", $str);
    // $str = str_replace( "'", "", $str);
    // $str = str_replace( "'", "", $str);
    // $str = str_replace( 'ASCII 0x08', "", $str);
    // $str = str_replace( '"', "", $str);
    // $str = str_replace( '"', "", $str);
    // $str = str_replace("&gt", "", $str);
    // $str = str_replace("&lt", "", $str);
    $str = str_replace("<SCRIPT>", "", $str);
    $str = str_replace("</SCRIPT>", "", $str);
    $str = str_replace("<script>", "", $str);
    $str = str_replace("</script>", "", $str);
    $str = str_replace("select","",$str);
    $str = str_replace("join","",$str);
    $str = str_replace("union","",$str);
    $str = str_replace("where","",$str);
    $str = str_replace("insert","",$str);
    $str = str_replace("delete","",$str);
    $str = str_replace("update","",$str);
    $str = str_replace("like","",$str);
    $str = str_replace("drop","",$str);
    $str = str_replace("DROP","",$str);
    $str = str_replace("create","",$str);
    $str = str_replace("modify","",$str);
    $str = str_replace("rename","",$str);
    $str = str_replace("alter","",$str);
    // $str = str_replace("cas","",$str);
    // $str = str_replace("&","",$str);
    $str = str_replace(">","",$str);
    $str = str_replace("<","",$str);
    $str = str_replace(" ",chr(32),$str);
    $str = str_replace(" ",chr(9),$str);
    $str = str_replace("    ",chr(9),$str);
    $str = str_replace("&",chr(34),$str);
    $str = str_replace("'",chr(39),$str);
    // $str = str_replace("<br />",chr(13),$str);
    $str = str_replace("<!--","",$str);
    $str = str_replace("../","",$str);
    $str = str_replace("./","",$str);
    $str = str_replace("Array","",$str);
    $str = str_replace("or 1='1'","",$str);
    $str = str_replace(";set|set&set;","",$str);
    $str = str_replace("`set|set&set`","",$str);
    $str = str_replace("--","",$str);
    $str = str_replace("OR","",$str);
    $str = str_replace('"',"",$str);
    $str = str_replace("*","",$str);
    $str = str_replace("+","",$str);
    $str = str_replace("'/","",$str);
    $str = str_replace("-- ","",$str);
    $str = str_replace(" -- ","",$str);
    $str = str_replace(" --","",$str);
    $str = str_replace("(","",$str);
    $str = str_replace(")","",$str);
    $str = str_replace("{","",$str);
    $str = str_replace("}","",$str);
    $str = str_replace("|","",$str);
    $str = str_replace("`","",$str);
    // $str = str_replace(";","",$str);
    // $str = str_replace("!=","",$str);
    // $str = str_replace("&&","",$str);
    // $str = str_replace("==","",$str);
    // $str = str_replace("#","",$str);
    // $str = str_replace("@","",$str);
    $str = str_replace("mailto:","",$str);
    // $str = str_replace("CHAR","",$str);
    // $str = str_replace("char","",$str);
    return $str;
}



function filterAll(&$perArr)
{
    global $db;
    foreach ($perArr as $key => $value)
    {
        $perArr[$key] = filter($db,$value);
        file_put_contents("aaa.txt","b.txt",FILE_APPEND);
    }
}



function uuid()

{
    $chars = md5(uniqid(mt_rand(), true));
    $uuid = substr ( $chars, 0, 8 ) . '-'
            . substr ( $chars, 8, 4 ) . '-'
            . substr ( $chars, 12, 4 ) . '-'
            . substr ( $chars, 16, 4 ) . '-'
            . substr ( $chars, 20, 12 );
    return $uuid;
}

function getIP()
{
    static $realip;
    if (isset($_SERVER)){
        if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            $realip = $_SERVER["HTTP_CLIENT_IP"];
        } else {
            $realip = $_SERVER["REMOTE_ADDR"];
        }
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR")){
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv("HTTP_CLIENT_IP")) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else {
            $realip = getenv("REMOTE_ADDR");
        } 
    }
    return $realip;
}

function GetRandomString($str){
      $str = md5(uniqid(md5(microtime(true)),true));
      $token = md5($str.$phone);
      
      return $token;
}

function CreateUserID($user,$uuid)
{
    global $db;
    $appid =  GetRandomString($uuid);
    $appkey = GetRandomString($uuid);
    $sql = "INSERT INTO `user_info` (`id`, `appid`, `key`, `num`, `any_num`, `free`, `user_uuid`) VALUES (NULL, '$appid', '$appkey', '50', '0', 'true', '$uuid')";
    if ($db->query($sql))
    {
        return true;
    }
    else
    {
        return false;
    }
}
function GetUserInfo($uuid)
{
    
    global $db;
    $info = array(
        "msg"=>&$msg,
        "status"=>&$status,
        "appid"=>&$appid,
        "appkey"=>&$appkey,
        "num"=>&$num,
        "any_num"=>&$any_num
        );
    $sql = "SELECT * FROM `user_info` WHERE `user_uuid` = '$uuid'";
    $result = $db->query($sql);
    if ($result)
    {
        $rows = mysqli_fetch_array($result);
        $appid = $rows['appid'];
        $appkey = $rows['key'];
        $num = $rows['num'];
        $any_num = $rows['any_num'];
        $status = 200;
        $msg = "success";
        return(json_encode($info));
    }
    else
    {
        $status = 403;
        $msg = "DataQueryFailed";
        return(json_encode($info));
    }

}

function GetToken($passwd,$date,$UA){
	$token = md5($passwd . strtotime($date) . $UA);
	return $token;
}

//获取当天相同的下载记录
function QueryDownloadFiles($url)
{
    global $db;
    $time = date("Y-m-d",time());
    $sql = "SELECT * FROM `down_info` WHERE `time` LIKE '$time%' AND `filename` != 'NULL' AND `md5` != 'FILE_EXISTS' AND `url` = '$url' ORDER by `id` DESC";
    // die($sql);
    $result = $db->query($sql);
    if ($result)
    {
        $rows = mysqli_fetch_array($result);
        if ($rows[0] >= 1)
        {
            $stime = date("Y-m-d", strtotime( $rows['time']));
            if ($time == $stime)
            {
                
                return $rows;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }
}


?>