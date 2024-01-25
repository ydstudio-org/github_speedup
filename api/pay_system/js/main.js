// JavaScript Document
var out_trade_no,trade_no,trade_status,type,money,PAY_MONEY,COM_NAME,timer,dia_btn,dia_result,dia_title,canvas;

trade_status="TRADE_SUCCESS";
 document.onreadystatechange = function () {
    if(document.readyState === 'complete'){
        dia_btn = document.getElementById("dia_btn");
        dia_result = document.getElementById("result");
        dia_title = document.getElementById("staticBackdropLabel");
        canvas = document.getElementById('canvas');
    }
 };

function GetPayInfo()
{
    // var PAY_MONEY,COM_NAME;
    xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var obj =JSON.parse(xmlhttp.responseText);
            PAY_MONEY = obj.PAY_MONEY;
            COM_NAME = obj.COM_NAME;
            money = PAY_MONEY;
	        var PaySel=document.getElementById("type");
	        var index=PaySel.selectedIndex;
	        type = PaySel.options[index].value;
	        var args = "type=" + type + "&appid=" + document.getElementById("appid").value + "&WIDsubject=" +  COM_NAME + "&WIDtotal_fee=" + PAY_MONEY;
	        console.log(args);
	        Curl("POST","https://api.service.linxi.info/down_files/pay_system/epayapi.php",args);
		}
	}
	xmlhttp.open("GET","https://api.service.linxi.info/down_files/pay_system/functions.php?id=pay_info",true);
	xmlhttp.send();

	
}

function drawImageOnCanvas(base64Str) {
    var canvas = document.getElementById('canvas');
    var context = canvas.getContext('2d');
    var img = new Image();
    img.onload = function() {
        canvas.width = img.width;
        canvas.height = img.height;
        context.drawImage(img, 0, 0);
    };
    img.src = base64Str;
    return canvas.toDataURL();
}



function CheckData()
{

    dia_btn.style.display = "";
    dia_result.innerHTML = "";
    dia_title.innerHTML = "请扫码支付";
    canvas.style.display = "";
	var PaySel=document.getElementById("type");
    var AppId = document.getElementById("appid").value;

	var index=PaySel.selectedIndex;
	if (AppId.length != 32)
	{
	    
	    alert("AppID长度不匹配");
	    throw new Error(1);
	}
	if (PaySel.options[index].value == "")
		{
			alert("未选择支付方式");
			throw new Error(2);
		}
		else
		{
        	if (AppId == "")
        	{
        	    alert("AppId 不可为空");
        	    throw new Error(3);
        	}
        	else
        	{
        	   // 验证通过开始支付回调
                GetPayInfo();
        	}
		}
	
}



function createDialog(data)
{
    var obj =JSON.parse(data);
    if(obj.code != 200){
        alert(obj.msg);
        
    }else{
    drawImageOnCanvas(obj.qrcode);
    out_trade_no = obj.sys_id;
    trade_no = obj.id;
    var dia = document.getElementById("submit");
	dia.click();
	timer = setInterval(CheckPayStatus, 1500,obj.id);
    }
}

function Curl(method,url,args)
{
    var data;
	var xmlhttp;
	if (window.XMLHttpRequest)
	{
		//  IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
		xmlhttp=new XMLHttpRequest();
	}
	else
	{
		// IE6, IE5 浏览器执行代码
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			 createDialog(xmlhttp.responseText);
		}
	}
	xmlhttp.open("POST",url,true);

	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.send(args);
	}

function repeatEvery(func, interval) {
    return setInterval(func, interval);
}

var num = 0;

function CheckPayStatus(id)
{

    var xmlhttp;
	if (window.XMLHttpRequest)
	{
		//  IE7+, Firefox, Chrome, Opera, Safari 浏览器执行代码
		xmlhttp=new XMLHttpRequest();
	}
	else
	{
		// IE6, IE5 浏览器执行代码
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			 var obj =JSON.parse(xmlhttp.responseText);
			 if (obj.status == 0)
			 {
			            UpgeadeDialogContent();
			            clearInterval(timer);
			 }
		}
	}
	xmlhttp.open("GET","https://api.service.linxi.info/down_files/pay_system/payCheck.php?id=" + id, true);
	xmlhttp.send();
}

function UpgeadeDialogContent()
{

    var xmlhttp;
	xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			 var obj =JSON.parse(xmlhttp.responseText);

			 if (obj.status == 200)
			 {
			    canvas.style.display = "none";
                dia_btn.style.display = "none";
                dia_result.innerHTML = "<font color=\"green\">" + obj.msg + "</font>";
                dia_title.innerHTML = "订单完成";

                
			 }
			 else
			 {
			    canvas.style.display = "none";
			    dia_btn.style.display = "none";
                dia_result.innerHTML = "<font color=\"red\">" + obj.msg + "</font>";
                dia_title.innerHTML = "订单失败";

			 }
		}
	}

	var pam = "out_trade_no=" + out_trade_no  + "&trade_no=" + trade_no +"&trade_status=" + trade_status + "&type=" + type + "&money=" + money;
	xmlhttp.open("GET","https://api.service.linxi.info/down_files/pay_system/return_url.php?" + pam  ,true);
	xmlhttp.send();
}
function CancelPayment(){
    clearInterval(timer);
}
