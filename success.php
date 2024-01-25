<?php
$download_link = $_POST['link'];
$filename = $_POST['filename'];
$md5 = $_POST['md5'];
$size = $_POST['size'];
$key = $_POST['key'];
if ($key != "5c7258332d969dbe656487f0fc761ce0")
{
    die("非法调用！");
}
$html = <<<EOF
<!DOCTYPE html>
<html>
 
	<head>
		<title>文件下载完成</title>
		<link rel="stylesheet" href="https://static.linxi.info/mdui/css/mdui.min.css"/>
		<style type="text/css">
			/*表格样式*/			
			table {
				width: 90%;
				background: #ccc;
				margin: 10px auto;
				border-collapse: collapse;/*border-collapse:collapse合并内外边距(去除表格单元格默认的2个像素内外边距*/	
			}				
			th,td {
				height: 25px;
				line-height: 25px;
				text-align: center;
				border: 1px solid #ccc;
			}		
			th {
				background: #eee;
				font-weight: normal;
			}		
			tr {
				background: #fff;
			}		
			tr:hover {
				background: #00ccc8;
			}		
			td a {
				color: #06f;
				text-decoration: none;
			}		
			td a:hover {
				color: #06f;
				text-decoration: underline;
			}
		</style>
	</head>
 
	<body>
	<div class="mdui-table-fluid">
		<table class="mdui-table mdui-table-hoverable">
			<tr>
 
				<!-- th为表格标题栏-->
 
				<th>文件名称</th>
 
				<th>文件大小</th>
 
				<th>文件MD5</th>
 
				<th>下载链接</th>
 
				<th>文件描述</th>
			</tr>
			<tr>
 
				<td>$filename</td>
 
				<td>$size</td>
 
				<td>$md5</td>
 
				<td>
					<a href="$download_link">下载</a>
				</td>
				
				<td>暂无</td>
			</tr>
		</table>
		</div>
		<script src="https://static.linxi.info/mdui/js/mdui.min.js"></script>
	</body>
 
</html>
EOF;

echo($html);
?>