<!--//
	
	var timer;
	
	function showform(i)
	{
		if (document.getElementById("tablean"+i).style.display == "none") 
		{
			document.getElementById("tablean"+i).style.display = "block";
			window.event.srcElement.innerText = "Закрыть форму";
		}
		else
		{
			document.getElementById("tablean"+i).style.display = "none";
			window.event.srcElement.innerText = "Ответить";
		}
		return false;
	}
	function arrowclick(i, id, timer)
	{
		if (document.getElementById("tablemes"+i).style.display == "none")
		{
			document.getElementById("tablemes"+i).style.display = "block";
			document.getElementById("img"+i).src = "img/upar.gif";
			document.getElementById("opened").innerText = document.getElementById("opened").innerText - (-1);
			timer = setTimeout("seenmes("+id+");", 2000);
		}
		else
		{
			window.clearTimeout(timer);
			document.getElementById("tablemes"+i).style.display = "none";
			document.getElementById("tablean"+i).style.display = "none";
			document.getElementById("img"+i).src = "img/dnar.gif";
			document.getElementById("href"+i).innerText = "Ответить";
			document.getElementById("opened").innerText = document.getElementById('opened').innerText - 1;
			if(document.getElementById("opened").innerText == "0") setTimeout("checkmes();", 5000);
		}
		return timer;
	}
	function getHTTPRequestObject() {
		var xmlHttpRequest;
		/*@cc_on
		@if (@_jscript_version >= 5)
		try {
			xmlHttpRequest = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (exception1) {
			try {
				xmlHttpRequest = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (exception2) {
				xmlHttpRequest = false;
			}
		}
		@else
		xmlhttpRequest = false;
		@end @*/
		
		if (!xmlHttpRequest && typeof XMLHttpRequest != "undefined") {
			try {
				xmlHttpRequest = new XMLHttpRequest();
			}
			catch (exception) {
				xmlHttpRequest = false;
			}
		}
		return xmlHttpRequest;
	}
	function getRequestBody(oForm) { 
		var aParams = new Array();
		for(var i = 0; i < oForm.elements.length; i++) {
			var sParam = encodeURIComponent(oForm.elements[i].name);
			sParam += "=";
			sParam += encodeURIComponent(oForm.elements[i].value);
			aParams.push(sParam);
		}
		return aParams.join("&");
	}
	function seenmes(id)
	{
		var oXmlHttp = getHTTPRequestObject();
		if(oXmlHttp)
		{
			oXmlHttp.open("GET", "mail.php?act=seen&mid="+id, true);
			oXmlHttp.send();
		}
	}
	function delmes(i, id)
	{
		var oXmlHttp = getHTTPRequestObject();
		if(oXmlHttp)
		{
			oXmlHttp.open("GET", "mail.php?act=delete&mid="+id, true);
			oXmlHttp.onreadystatechange = function()
			{
				if(oXmlHttp.readyState == 1)
				{
					document.getElementById("uajaxloader").style.display = "block";
				}
				if(oXmlHttp.readyState == 4)
				{
					document.getElementById("opened").innerText = document.getElementById("opened").innerText - 1;
					checkmes(1);
				}
			}
        	oXmlHttp.send();
        }
	}
	function sendmes(i)
	{
		var oForm = document.getElementById("form"+i);
        var sBody = getRequestBody(oForm);
		var oXmlHttp = getHTTPRequestObject();
		if(oXmlHttp)
		{
			oXmlHttp.open("POST", "mail.php?act=sendajax", true);
			oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			oXmlHttp.onreadystatechange = function()
			{
				if(oXmlHttp.readyState == 1)
				{
					document.getElementById("ajaxloader"+i).style.display = "block";
				}
				if(oXmlHttp.readyState == 4)
				{
					document.getElementById("opened").innerText = document.getElementById("opened").innerText - 1;
					checkmes(1);
				}
			}
			oXmlHttp.send(sBody);
		}
	}
	function checkmes(man)
	{
		if((document.getElementById("opened").innerText == "0" || man == 1) && document.getElementById("auto").checked == true)
		{
			var oXmlHttp = getHTTPRequestObject();
			if(oXmlHttp)
			{
				oXmlHttp.open("POST", "mail.php?act=checkajax", true);
				oXmlHttp.onreadystatechange = function()
				{
					if(oXmlHttp.readyState == 1)
					{
						document.getElementById("uajaxloader").style.display = "block";
					}
					if(oXmlHttp.readyState == 4)
					{
						var inner = '<table id="messages" border="1" cellspacing="0" cellpadding="3" style="border-style: solid; border-color: #c82c2a; border-width: 1" width="100%">';
						var resp = oXmlHttp.responseText.split(";|/");
						if(resp[0] == 0)
						{
							inner += '<tr><td width="100%" align="center">Новых сообщений нет.</td></tr>';
						}
						else
						{
							for(i=1; i<=resp[0]; i++)
							{
								inner += "<tr id='tr"+i+"' style='cursor: default;' onmouseover='javascript: this.bgColor = \"#e5e5e5\";' onmouseout='javascript: this.bgColor = \"#eeeeee\";'>";
								inner += "<td class='b' style='border-style: solid; border-color: #aaaaaa; border-width: 1' width='100' valign='top'><b>"+resp[(5*i-4)]+"</b></td>";
								inner += "<td width='100%' style='border-style: solid; border-color: #aaaaaa; border-width: 1; padding-left: 5 px;'><font class='b'>Тема:</font> "+resp[(5*i-3)]+"<br><div align='left' id='tablemes"+i+"' style='display: none;'><font class='b'>Текст:</font> "+resp[(5*i-2)]+"<br>";
								inner += "<br><a id='href"+i+"' href='#' onclick='javascript: showform("+i+");'>Ответить</a> <a href='#' onclick='javascript: var a = confirm(\"Удалить сообщение?\"); if(a) delmes("+i+", "+resp[(5*i)]+"); return false;'>Удалить</a><br><br></div>";
								inner += "<table align='left' id='tablean"+i+"' style='display: none;'><form id='form"+i+"' action='javascript: sendmes("+i+");' method='get'><input type='hidden' name='recepient' value='"+resp[(5*i-1)]+"'>";
								inner += "<tr><td class='b' align='right'>Тема:</td><td><input type='text' name='theme' value=`"+resp[(5*i-3)]+"` maxlength='100'></td></tr>";
								inner += "<tr><td class='b' align='right' valign='top'>Текст:</td><td><textarea name='text' style='background-color: #eeeeee;' rows='4' cols='30'></textarea></td></tr>";
								inner += "<tr><td></td><td><input type='submit' name='go' value='Отправить'> <img id='ajaxloader"+i+"' src='img/ajax-loader.gif' style='display: none;'></td></tr>";
								inner += "</form></table></td><td valign='top' style='border-style: solid; border-color: #aaaaaa; border-width: 1; padding-top: 8 px' onclick='javascript: timer = arrowclick("+i+", "+resp[(5*i)]+", timer);'><img src='img/dnar.gif' id='img"+i+"'></td></tr>";
							}
						}
						inner += "</table>";
						document.getElementById("divmes").innerHTML = inner;
						if(resp[0] > 0) document.getElementById("leftmesnum").innerText = "["+resp[0]+"]";
						else document.getElementById("leftmesnum").innerText = "";
						document.getElementById("uajaxloader").style.display = "none";
					}
				}
				oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
				oXmlHttp.send();
			}
		}
		setTimeout("checkmes();", 5000);
	}
	setTimeout("checkmes();", 5000);
	//-->