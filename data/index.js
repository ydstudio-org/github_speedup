
function loadTable()
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
		    var table=document.getElementById("data");
			var json =xmlhttp.responseText;
			var data = JSON.parse(json);
			var jdata = Object.values(data.data);
			console.log(jdata.length);
			console.log(table);
			jdata.forEach((item) => {
            var row=table.insertRow(1);
	        var cell1=row.insertCell(0);
	        var cell2=row.insertCell(1);
	        var cell3=row.insertCell(2);
	        var cell4=row.insertCell(3);
	        var cell5=row.insertCell(4);
	        cell1.innerHTML=item[0];
	        cell2.innerHTML=item[1];
	        cell3.innerHTML=item[2];
	        cell4.innerHTML=item[3];
	        cell5.innerHTML=item[4];
            });
		}
	}
	xmlhttp.open("GET","api.php",true);
	xmlhttp.send();
}


function loadPage(pageName,divID)
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
		    console.log(xmlhttp.responseText);
		    var dom=document.getElementById(divID);
		    console.log(dom);
            dom.innerHTML = xmlhttp.responseText;
            
		}
	}
	xmlhttp.open("GET","pages/" + pageName + ".txt",true);
	xmlhttp.send();
}

function active(domName)
{
    var dom = document.getElementById(domName);
    document.getElementById("home").classList.remove("active");
     document.getElementById("detail").classList.remove("active");
    dom.className = "active";
}

function home()
{
    loadPage("main","main")
}
function detail()
{
    loadPage("tables","main")
    loadTable();
}
