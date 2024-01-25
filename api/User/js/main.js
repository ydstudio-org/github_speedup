// JavaScript Document




function checkData()
{
	var user = document.getElementById('user');
	var pass = document.getElementById('pass');
	var subit = document.getElementById('sub');
	if (user.value == '' || pass.value == '')
		{
			var info = document.getElementById('info');
			info.innerHTML = "请输入登录凭据！";
			
		}
	else
		{
			loading(true);
			verifyUP();
			
		}
	
	
}


function getCookie(cname)
{
  var name = cname + "=";
  var ca = document.cookie.split(';');
  for(var i=0; i<ca.length; i++) 
  {
    var c = ca[i].trim();
    if (c.indexOf(name)==0) return c.substring(name.length,c.length);
  }
  return "";
}

function setCookie(cname,cvalue,exdays)
{
  var d = new Date();
  d.setTime(d.getTime()+(exdays*24*60*60*1000));
  var expires = "expires="+d.toGMTString();
  document.cookie = cname + "=" + cvalue + "; " + expires;
}


function verifyUP()
{
	
	var args = "user=" + document.getElementById('user').value + "&pass=" + document.getElementById('pass').value;
	console.warn(args);
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
			// document.getElementById("info").innerHTML=xmlhttp.responseText;
			var json = JSON.parse(xmlhttp.responseText);
			document.getElementById('info').innerHTML = json.cont;
			mdui.snackbar({message: json.msg,buttonText: '好'});
			mdui.mutation();
		}
	}

	xmlhttp.open("POST","login.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send(args);
	loading(false);
}


function loading(operation)
{
	var loading = document.getElementById('loading');
	if (operation == true)
	{
		loading.hidden = false;
	}
	else
	{
		loading.hidden = true;
	}

}


function LoginOut()
{
	setCookie('login_status','false',9999);
	mdui.snackbar({message: '已经退出账号',buttonText: '好'});
}

var Loginbt = document.getElementById('lout');

if (getCookie("login_status") == "true")
{
	mdui.snackbar({message: '当前已经登陆',buttonText: '好'});
	Loginbt.hidden = false;
}
else
{
	Loginbt.hidden = true;
	mdui.snackbar({message: '没有账号登录',buttonText: '好'});
}



function sendCode()
{

	var email = document.getElementById('email').value ;
	var user = document.getElementById('user').value;
	var password = document.getElementById('pass').value;
	if (user.length > 12||user.length < 6)
	{
		mdui.snackbar({message: "用户名长度不合规",buttonText: '好'});
	}
	else
	{
		if (password.length < 6||password.length > 16)
		{
		mdui.snackbar({message: "密码长度不合规",buttonText: '好'});

		}
		else
		{
			if (email == '' || user == ''|| password == '')
			{
				document.getElementById('info').innerHTML = "数据不完整";
			}
			else
			{
				Send();

			}
		}
	}

	function Send()
	{

			
		var args = "email=" + email + "&pass=" + password + "&user=" + user + "&oper=register";
		console.warn(args);
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
				console.log(xmlhttp.responseText);
				// document.getElementById("info").innerHTML=xmlhttp.responseText;
				var json = JSON.parse(xmlhttp.responseText);

				if (json.status == 200)
				{
					var bt = document.getElementById('sendCode');
					var num = 60;
					var timer = null;
					timer = setInterval(function (){
						num--;
						bt.innerHTML = num + "秒后重发";
						bt.disabled = true;
						if (num == 0)
						{
							clearInterval(timer);
							bt.disabled = false;
							bt.innerHTML = "重新发送";
						}
					},1000);
					mdui.snackbar({message: "验证码已经发送,请注意查收",buttonText: '好'});
					document.getElementById('info').innerHTML = json.cont;

				}
				else
				{
					mdui.snackbar({message: json.msg,buttonText: '好'});
					document.getElementById('info').innerHTML = json.cont;
				}

			}
		}
	
		xmlhttp.open("POST","register.php",true);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send(args);
	}
	
}

function register()
{
	var email = document.getElementById('email').value ;
	var code = document.getElementById('code').value;
	var args = "email=" + email + "&code=" + code + "&oper=verify";
	if (code.length != 6)
	{
		mdui.snackbar({message: "非法的校验码",buttonText: '好'});
	}
	else
	{
	}
	console.warn(args);
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
			// document.getElementById("info").innerHTML=xmlhttp.responseText;
			var json = JSON.parse(xmlhttp.responseText);
			document.getElementById('info').innerHTML = json.cont;
			mdui.snackbar({message: json.msg,buttonText: '好'});
			mdui.mutation();
		}
	}

	xmlhttp.open("POST","register.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send(args);
}