<?php
$error_page1 = <<<EOT
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>限制</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            color: #444
        }

        body {
            font-size: 14px;
            font-family: "宋体"
        }

        .main {
            width: 600px;
            margin: 10% auto;
        }

        .title {
            background: red;
            color: #fff;
            font-size: 16px;
            height: 40px;
            line-height: 40px;
            padding-left: 20px;
        }

        .content {
            background-color: #f3f7f9;
            height: 300px;
            border: 1px dashed #c6d9b6;
            padding: 20px
        }

        .t1 {
            border-bottom: 1px dashed #c6d9b6;
            color: #ff4000;
            font-weight: bold;
            margin: 0 0 20px;
            padding-bottom: 18px;
        }

        .t2 {
            margin-bottom: 8px;
            font-weight: bold
        }

        ol {
            margin: 0 0 20px 22px;
            padding: 0;
        }

        ol li {
            line-height: 30px
        }

        #login_click {
            margin-top: 32px;
            height: 40px;
        }

        #login_click a {


            text-decoration: none;
            background: #2f435e;
            color: #f2f2f2;

            padding: 10px 30px 10px 30px;
            font-size: 16px;
            font-family: 微软雅黑, 宋体, Arial, Helvetica, Verdana, sans-serif;
            font-weight: bold;
            border-radius: 3px;

            -webkit-transition: all linear 0.30s;
            -moz-transition: all linear 0.30s;
            transition: all linear 0.30s;

        }

        #login_click a:hover {
            background: #385f9e;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="title">网站管理员提醒您：</div>
        <div class="content">
            <p class="t1">您当前下载次数已经超过单天最大限制(15次)</p>
            <p class="t2">如何解决：</p>
            <ol>
                <li>联系网站管理员，购买每天额外次数(1.5RMB/15次/IP,仅当日有效，充值不可叠加，次数用完前请勿重新充值，否则后果自负)；</li>
                <li>等待到次日自动恢复；</li>
                <li>你的IP:
EOT;
$error_page2 = <<<EOT
</li>

            </ol>
            <div id="login_click">
                <a id="btlogin" onclick="window.close()">关闭</a>
            </div>
        </div>


    </div>
</body>

</html>

EOT
?>