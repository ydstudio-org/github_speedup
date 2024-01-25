<?php
include("../core/database_config.php");

$mysql = new mysqli(HOST,USER,PASSWORD,TABLE);
$msg = "";
$code = 200;
$data = [];
$json = [
    'msg'=>&$msg,
    'status'=>&$code,
    'data'=>&$data
    ];


$sql = "SELECT id,time,ip,url,status FROM `down_info`";
$result = $mysql->query($sql);
if ($result)
{
    while($row=mysqli_fetch_array($result))
    {
    $data[] = $row;
    }


    $msg = "success";
    header("content-type: application/json");
    echo json_encode($json);
}