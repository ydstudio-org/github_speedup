<!DOCTYPE html>
<html lang="cn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- 新 Bootstrap5 核心 CSS 文件 -->
    <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/5.1.1/css/bootstrap.min.css">
    <title>捐助我们</title>
    <style>
        body {
            background-color: #f2f2f2;
            font-family: Arial, sans-serif;
        }

        .form-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: 50px auto;
            max-width: 800px;
            padding: 30px;
        }

        .form-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group select {
            border-radius: 5px;
            border: none;
            box-shadow: inset 0px 0px 5px rgba(0, 0, 0, 0.1);
            display: block;
            font-size: 16px;
            padding: 10px;
            width: 100%;
        }

        .form-group select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image:
                linear-gradient(45deg, transparent 50%, gray 50%),
                linear-gradient(135deg, gray 50%, transparent 50%);
            background-position:
                calc(100% - 20px) calc(1em + 2px),
                calc(100% - 15px) calc(1em + 2px);
            background-size:
                5px 5px,
                5px 5px;
            background-repeat: no-repeat;
        }

        .form-group button[type="submit"] {
            background-color: #4CAF50;
            border-radius: 5px;
            border: none;
            color: #fff;
            cursor: pointer;
            display: block;
            font-size: 16px;
            margin-top: 20px;
            padding: 10px;
            width: auto;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <h1>捐助我们</h1>
        <form>

            <div class="input-group flex-nowrap">
                <div class="input-group-prepend">
                    <span class="input-group-text" id="addon-wrapping">🏠</span>
                </div>
                <input id="appid" type="text" class="form-control" placeholder="AppID" aria-label="AppID"
                    aria-describedby="addon-wrapping" value="<?php echo $_GET['appid']; ?>">
            </div>
            <div class="form-group">
            </div>


            <div class="form-group">
                <label for="gender">支付方式</label>
                <select class="form-select" id="type">
                    <option value="">请选择支付方式</option>
                    <option value="wxpay">微信支付</option>
                    <option value="alipay">支付宝</option>
                    <option value="paypal">PayPal</option>
                </select>
            </div>

            <button type="button" style=" position: relative; top: 8%;" class="btn btn-primary" onclick="CheckData()">
                立即捐赠
            </button>
            <button type="button" id="submit" data-bs-toggle="modal" data-bs-target="#staticBackdrop" hidden> </button>
        </form>


        <!-- Modal -->
        <div class="modal fade" style="backdrop-filter: blur(6px)" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">请扫码支付</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="CancelPayment()"></button>
                    </div>
                    <div class="modal-body">


                        <canvas id="canvas"></canvas>
                        <p id="result"></p>

                    </div>
                    <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="close_btn" data-bs-dismiss="modal" onclick="CancelPayment()" >关闭</button>
          <button type="button" class="btn btn-primary" id="dia_btn" onclick="UpgeadeDialogContent()">我已经完成支付</button>
                    </div>
                </div>
            </div>
        </div>
<hr>
        <label><h6>为什么要捐赠我们?</h6></label>
        <ul>
        <!--<li><del>因为我是homo，你也是homo，所以你要爆一个一个一个金币</del></li>-->
        <li>因为服务器资源并不是免费的</li>
        <li>付费可以让我们做的更好，服务的性能更高</li>
        <li>没想好，要不你来建言献策（bushi</li>


        </ul>
        <noscript><h1><font color="red">你的浏览器不支持JavaScript!!!</font></h1></noscript>
    </div>
    
    
</div>
    <script src="https://cdn.staticfile.org/twitter-bootstrap/5.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery-3.4.1.min.js" language="javascript"></script>
    <script src="js/main.js" language="javascript"></script>

</body>

</html>