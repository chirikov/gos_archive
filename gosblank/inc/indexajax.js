<!--//
function main()
{
	document.getElementById("tableresult").style.display = "none";
	document.getElementById('activate').style.display = 'none';
	document.getElementById('register').style.display = 'none';
	document.getElementById('main').style.display = 'block';
}
function register()
{
	document.getElementById("tableresult").style.display = "none";
	document.getElementById('main').style.display = 'none';
	document.getElementById('activate').style.display = 'none';
	document.getElementById('register').style.display = 'block';
}
function activate()
{
	document.getElementById("tableresult").style.display = "none";
	document.getElementById('main').style.display = 'none';
	document.getElementById('register').style.display = 'none';
	document.getElementById('activate').style.display = 'block';
}
function selchg()
{
	if(f1.month.value == 2)
	{
		document.getElementById('selday').remove(30);
		document.getElementById('selday').remove(29);
		if(document.getElementById('selyear').value % 4 != 0)
		{
			document.getElementById('selday').remove(28);
		}
		else
		{
			if(document.getElementById('selday').options.length == 28)
			{
				var elem = document.createElement('option');
				elem.id = 'opt29';
				elem.value = '29';
				elem.text = '29';
				document.getElementById('selday').add(elem, 28);
			}
		}
	}
	else
	{
		if(f1.month.value == 4 || f1.month.value == 6 || f1.month.value == 9 || f1.month.value == 11)
		{
			if(document.getElementById('selday').options.length == 28)
			{
				var elem = document.createElement('option');
				elem.id = 'opt29';
				elem.value = '29';
				elem.text = '29';
				document.getElementById('selday').add(elem, 28);
			}
			if(document.getElementById('selday').options.length == 29)
			{
				var elem = document.createElement('option');
				elem.id = 'opt30';
				elem.value = '30';
				elem.text = '30';
				document.getElementById('selday').add(elem, 29);
			}
			document.getElementById('selday').remove(30);
		}
		else
		{
			if(f1.month.value == 1 || f1.month.value == 3 || f1.month.value == 5 || f1.month.value == 7 || f1.month.value == 8 || f1.month.value == 10 || f1.month.value == 12)
			{
				if(document.getElementById('selday').options.length == 28)
				{
					var elem = document.createElement('option');
					elem.id = 'opt29';
					elem.value = '29';
					elem.text = '29';
					document.getElementById('selday').add(elem, 28);
				}
				if(document.getElementById('selday').options.length == 29)
				{
					var elem = document.createElement('option');
					elem.id = 'opt30';
					elem.value = '30';
					elem.text = '30';
					document.getElementById('selday').add(elem, 29);
				}
				if(document.getElementById('selday').options.length == 30)
				{
					var elem = document.createElement('option');
					elem.id = 'opt31';
					elem.value = '31';
					elem.text = '31';
					document.getElementById('selday').add(elem, 30);
				}
			}
		}
	}
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
function regdone()
{
	var oXmlHttp = getHTTPRequestObject();
	var sBody = getRequestBody(document.getElementById("f1"));
	if(oXmlHttp)
	{
		oXmlHttp.open("POST", "registration.php?act=ajaxregdone", true);
		oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 1)
			{
				//document.getElementById("uajaxloader").style.display = "block";
			}
			if(oXmlHttp.readyState == 4)
			{
				var result = oXmlHttp.responseText;
				switch(result)
				{
					case "esurname":
						document.getElementById("tdresult").style.backgroundColor = "Red";
						document.getElementById("tdresult").innerText = "Вы не ввели фамилию";
					break;
					case "ename":
						document.getElementById("tdresult").style.backgroundColor = "Red";
						document.getElementById("tdresult").innerText = "Вы не ввели имя";
					break;
					case "eemail":
						document.getElementById("tdresult").style.backgroundColor = "Red";
						document.getElementById("tdresult").innerText = "Вы не ввели E-mail";
					break;
					case "epass":
						document.getElementById("tdresult").style.backgroundColor = "Red";
						document.getElementById("tdresult").innerText = "Вы не ввели пароль";
					break;
					case "epassdifferent":
						document.getElementById("tdresult").style.backgroundColor = "Red";
						document.getElementById("tdresult").innerText = "Пароли не совпадают";
					break;
					case "eemailexists":
						document.getElementById("tdresult").style.backgroundColor = "Red";
						document.getElementById("tdresult").innerText = "Такой E-mail уже зарегистрирован.";
						activate();
						f2.email.value = f1.email.value;
					break;
					case "ok":
						document.getElementById("tdresult").style.backgroundColor = "Blue";
						document.getElementById("tdresult").innerText = "Вы зарегистрированы. На Ваш E-mail отправлено письмо с кодом активации. Введите его ниже или пройдите по ссылке в письме для завершения регистрации.";
						activate();
						f2.email.value = f1.email.value;
					break;
					default:
						document.getElementById("tdresult").style.backgroundColor = "Red";
						document.getElementById("tdresult").innerText = "Ошибка регистрации";
				}
				document.getElementById("tableresult").style.display = "block";
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
       	oXmlHttp.send(sBody);
	}
}
function actdone()
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		oXmlHttp.open("GET", "registration.php?act=ajaxactdone&email="+f2.email.value+"&actcode="+f2.actcode.value, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 1)
			{
				//document.getElementById("uajaxloader").style.display = "block";
			}
			if(oXmlHttp.readyState == 4)
			{
				var result = oXmlHttp.responseText;
				document.getElementById("tdresult").style.backgroundColor = "Red";
				switch(result)
				{
					case "eemail":
						document.getElementById("tdresult").innerText = "Неверный E-mail";
					break;
					case "ecode":
						document.getElementById("tdresult").innerText = "Неверный код";
					break;
					case "eemailacted":
						document.getElementById("tdresult").innerText = "E-mail уже активирован";
					break;
					case "ok":
						location = "profile.php?act=home";
					break;
					default:
						document.getElementById("tdresult").innerText = "Ошибка активации";
				}
				document.getElementById("tableresult").style.display = "block";
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
		oXmlHttp.send();
	}
}
function sendactcode()
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		oXmlHttp.open("GET", "registration.php?act=ajaxsendcode&email="+f2.email.value, true);
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 1)
			{
				//document.getElementById("uajaxloader").style.display = "block";
			}
			if(oXmlHttp.readyState == 4)
			{
				var result = oXmlHttp.responseText;
				document.getElementById("tdresult").style.backgroundColor = "Red";
				switch(result)
				{
					case "eemailacted":
						document.getElementById("tdresult").innerText = "E-mail уже активирован";
					break;
					case "eemail":
						document.getElementById("tdresult").innerText = "Неверный E-mail";
					break;
					case "ok":
						document.getElementById("tdresult").style.backgroundColor = "Blue";
						document.getElementById("tdresult").innerText = "Письмо отправлено";
					break;
				}
				document.getElementById("tableresult").style.display = "block";
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
		oXmlHttp.send();
	}
}
function logindone()
{
	var oXmlHttp = getHTTPRequestObject();
	//var sBody = getRequestBody(document.getElementById("flogin"));
	if(oXmlHttp)
	{
		//oXmlHttp.open("GET", "login.php?act=ajaxlogindone", true);
		oXmlHttp.open("GET", "http://login.userapi.com/auth?login=force&site=1768&email="+flogin.email.value"&pass="+flogin.pass.value, true);
		//oXmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 1)
			{
				//document.getElementById("uajaxloader").style.display = "block";
			}
			if(oXmlHttp.readyState == 4)
			{
				var result = oXmlHttp.responseText;
				switch(result)
				{
					case "eemail":
						document.getElementById("tdresult").style.backgroundColor = "Red";
						document.getElementById("tdresult").innerText = "Неверный E-mail";
					break;
					case "eemailunactive":
						document.getElementById("tdresult").style.backgroundColor = "Red";
						document.getElementById("tdresult").innerText = "E-mail не активирован";
						activate();
						f2.email.value = flogin.email.value;
					break;
					case "epass":
						document.getElementById("tdresult").style.backgroundColor = "Red";
						document.getElementById("tdresult").innerText = "Неверный пароль";
					break;
					case "ok":
						location = "profile.php?act=home";
					break;
				}
				document.getElementById("tableresult").style.display = "block";
			}
		}
		oXmlHttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
       	oXmlHttp.send(sBody);
	}
}
//-->