<?php
header("content-type: application/json");
require_once("../core/database_config.php");
if (!$_GET['id'] || is_null($_GET['id'])){
    exit("{\"code\":404,\"msg\":\"Function not Found\"}");
}
$FunctionId = $_GET['id'];

switch ($FunctionId) {
    case 'pay_info':
        $arr = array("PAY_MONEY"=>PAY_MONEY,"COM_NAME"=>PAY_ADD_NUM.COM_NAME);
        exit(json_encode($arr));
        break;
    case 'md5':
        if (!$_GET['md5'] || is_null($_GET['md5'])){
            exit("{\"code\":404,\"msg\":\"Sources not Found\"}");
            }
        $Sources = $_GET['md5'];
        $Sources = md5($Sources);
        // exit(md5($Sources));
        exit("{\"code\":200,\"data\":\"$Sources\"}");
    default:
        exit("{\"code\":404,\"msg\":\"Function not Found\"}");
        break;
}

$arr = array("PAY_MONEY"=>PAY_MONEY,"COM_NAME"=>PAY_ADD_NUM.COM_NAME);

exit(json_encode($arr));