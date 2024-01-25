<?php
require('get_user_info.class.php');
$info= new UserInfo();
$info->getUserIP();
$wz=$info->getGeo();
header("Content-Type: application/json");
echo($wz);
