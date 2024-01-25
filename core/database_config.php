<?php
// --------------数据库信息--------------
define("USER","");
define("PASSWORD","");
define("TABLE","");
define("HOST","127.0.0.1");
define("PORT","3306");
//----------------------------------------
//--------------网站相关配置--------------
define("DOWNLOAD_MAX_NUM",15);
//免费版APPID单IP一天最大下载次数
define("PAY_MONEY",0.01);
//单价
define("PAY_ADD_NUM",100);
define("COM_NAME","次下载套餐");
define("version","v1.3.2_stable");
define("EMAIL_AUTH_CODE","47e859ff1e599b5ef9d99c839c3f38c5");
define("NODE_REG_KEY","8d04cea4-25a3-b2b4-2b18-4e52533e1c8b");
//节点注册Key
define("SITE_URL","https://www.example.com/");
$tmp_date = date('Y-m-d');
//保存当前日期
//----------------------------------------

$mysql = new mysqli(HOST,USER,PASSWORD,TABLE);
if($mysql -> connect_errno){
        die('数据库连接失败：'.$mysql->connect_errno);
    }else{
        $mysql -> close();
    }
    
$serverList = array(
    "msg"=>'success',
    "data"=>
    [
        ['name' => '主节点','url'=>'https://api.service.linxi.info/down_files/index.php',"IsStream"=>false,"IPv6Only"=>false],
        ['name' => '主节点[流式]','url'=>'https://api.service.linxi.info/down_files/stream.php',"IsStream"=>true,"IPv6Only"=>false],
        ['name' => '华北联通','url'=>'http://proxydown1.nodes.linxi.info:51130/index.php',"IsStream"=>false,"IPv6Only"=>false],
        ['name' => '华北联通[流式]','url'=>'http://proxydown1.nodes.linxi.info:51130/stream.php',"IsStream"=>true,"IPv6Only"=>false]
    ],
    "status"=>200
    );

?>