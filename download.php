<?php
$file_name = $_GET['filename']; 
$file_name=str_replace('/','',$file_name);
$file_name=str_replace('\\','',$file_name);
if (trim($file_name) == ''||str_replace('.','',$file_name) == '')
{
    die('找不到文件');
}
$file_dir = "downloads/"; 
if (!file_exists($file_dir . $file_name)) { //检查文件是否存在 
 echo "找不到文件或文件已经过期:". $file_name; 
 exit;
}else{ 
 $file = fopen($file_dir . $file_name,"r"); // 打开文件 
 // 输入文件标签 
 header("Content-type: application/octet-stream"); 
 header("Accept-Ranges: bytes"); 
 header("Accept-Length: ".filesize($file_dir . $file_name)); 
 header("Content-Disposition: attachment; filename=" . $file_name); 
 // 输出文件内容 
 echo fread($file,filesize($file_dir . $file_name)); 
 fclose($file); 
 exit;
}