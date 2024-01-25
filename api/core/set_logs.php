<?php
include("database_config.php");
    //echo $dizhi;
    //echo $obj->provider; 
function set_logs($url,$user,$status,$size,$md5,$filename="NULL")
{
    require_once('api.php');
    $ip = get_real_ip();
    $pageurl = curPageURL();

    //$json = file_get_contents("https://ip.mcr.moe/?ip=$ip");
    //$arr = json_decode($json, true);
    //$obj = json_decode($json);
    //$dizhi =  $obj->area; 
    $timed = date('Y-m-d h:i:s');
    $riqi=date('Y-m-d h:i:s', time());
    $tmp_date = date('Y-m-d');
    $sj=date('Y-m-d', time());
    $path = 'core/logs/'. $sj .'   '. $user. '.log';
    $now = date("Y-m-d");
    if (!$user or !$url)
    {
        $path = 'core/logs/failed/'. $sj . '.log';
        //$log = $dizhi . '   ' . $ip . '  ' .$pageurl;
        $log = $user . '  ' .$pageurl;
        file_put_contents($path,$log.PHP_EOL, FILE_APPEND);
        return 'failed';
    }
    
    $log = $ip . '  '. $user . '  ' .$riqi . '  ' . $url. '     '. $status.  '     '.$size . ' MB';
    if ($status == "true"){
        $status1 = 1;
    }else{
        $status1 = 0;
    }
    $mysql = new mysqli(HOST,USER,PASSWORD,TABLE);
    $mysql -> set_charset('UTF-8');
    //设置字符集
    $sql = "INSERT INTO `down_info` (`id`, `time`, `ip`, `filename` , `url`, `size`, `md5`, `user`, `status`) VALUES (NULL, '$timed', '$ip', '$filename', '$url', '$size', '$md5', '$user', '$status1');";
    $mysql -> query($sql);
    //执行添加下载信息的sql语句
    $sql = "SELECT * FROM `down_num` WHERE `ip` LIKE '$ip'";
    $result = $mysql -> query($sql);
    //查询下载计数的表看有没有这个IP，如果没有就新建一个
    $num = $result -> num_rows;
    if ($num > 0){
        $sql = "SELECT * FROM `down_num` WHERE `date` = '$tmp_date' AND `ip` LIKE '$ip'";
        $result = $mysql -> query($sql);
        //查询计数的表看这个ip的时间是不是今天的如果不是就更新时间并且重置下载次数为1
        $num1 = $result -> num_rows;
        //echo $ip . ":" . $num1;
        if ($num1 >= 1){
            $sql = "SELECT * FROM `down_num` WHERE `ip` LIKE '$ip' AND `date` LIKE '$now';";
            $result = $mysql -> query($sql);
            $row = mysqli_fetch_array($result);
            $d_num = $row['num'];
            // echo("log:: " . $row['num'] . "<br />");
            // echo($sql . "<br />");
            // print_r($row);
            //echo "1:" . $d_num;
            $d_num = $d_num + 1;
            //echo "2:" . $d_num;
            $sql = "UPDATE `down_num` SET `num` = '$d_num' WHERE `down_num`.`ip` = '$ip' AND `date` = '$tmp_date';";
            $mysql -> query($sql);
        }else{
            //$sql = "UPDATE `down_num` SET `date` = '$tmp_date' WHERE `down_num`.`ip` = '$ip';";
            $sql = "INSERT INTO `down_num` (`id`, `date`, `ip`, `num`) VALUES (NULL, '$tmp_date', '$ip', '1');";
            $mysql -> query($sql);
        }
    }else{
        $sql = "INSERT INTO `down_num` (`id`, `date`, `ip`, `num`) VALUES (NULL, '$tmp_date', '$ip', '1');";
        $mysql -> query($sql);
    }
    $mysql -> close();
    file_put_contents($path,$log.PHP_EOL, FILE_APPEND);
    if (file_exists($path))
        return 'ok';
    else
        return 'failed_write';

}

?>