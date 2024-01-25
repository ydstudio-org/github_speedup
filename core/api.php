<?php

    function get_real_ip()
{

    $ip=FALSE;

    //客户端IP 或 NONE

    if(!empty($_SERVER["HTTP_CLIENT_IP"])){

        $ip = $_SERVER["HTTP_CLIENT_IP"];

    }

    //多重代理服务器下的客户端真实IP地址（可能伪造）,如果没有使用代理，此字段为空

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

        $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);

        if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }

        for ($i = 0; $i < count($ips); $i++) {

            if (!eregi ("^(10│172.16│192.168).", $ips[$i])) {

                $ip = $ips[$i];

                break;

            }

        }

    }

    //客户端IP 或 (最后一个)代理服务器 IP

    return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}
//$user_ip = get_real_ip()



function curPageURL()

{

  $pageURL = 'http';

  if ($_SERVER["HTTPS"] == "on")

  {
    $pageURL .= "s";
  }

  $pageURL .= "://";

 

  if ($_SERVER["SERVER_PORT"] != "80")

  {
    $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
  }

  else

  {
    $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
  }
  return $pageURL;
}





// 将JSON编码的字符串分配给PHP变量
//http://pv.sohu.com/cityjson?ie=utf-8
//require_once('http://pv.sohu.com/cityjson?ie=utf-8');
//echo $ip;
//$json = file_get_contents("https://ip.mcr.moe/?ip=$ipaddr");
//echo ($json);

// 将JSON数据解码为PHP关联数组

$arr = json_decode($json, true);


$obj = json_decode($json);

// 返回对象的访问值

echo $obj->area;   // Output: 65

echo $obj->provider;   // Output: 80

//echo $obj->cname;    // Output: 78

//echo $obj->Clark;   // Output: 90

?>