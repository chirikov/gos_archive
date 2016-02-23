<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Мир</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<meta http-equiv="Description" content="Онлайн игра Мир">
<meta http-equiv="Keywords" content="Мир, политика, экономика, общество, виртуальный">
<style type="text/css"><!--
--></style>
<script language="javascript" type="text/javascript">
<!--//

function mkstr(num, one, two, many)
{	
	var retv = num;
	if(num % 10 == 1 && num != 11) retv += one;
	else
	{
		if((num % 10 == 2 || num % 10 == 3 || num % 10 == 4)  && !(num > 10 && num < 15)) retv += two;
		else retv += many;
	}
	return retv;
}

function refresh()
{
	var intext = "";
	var dt2 = document.getElementById('dtd').innerText;
	var days = Math.floor(dt2/(60*60*24));
	dt2 = dt2 - days*60*60*24;
	var hours = Math.floor(dt2/3600);
	dt2 = dt2 - hours*3600;
	var minutes = Math.floor(dt2/60);
	dt2 = dt2 - minutes*60;

	if(days > 0) intext += mkstr(days, " день ", " дня ", " дней ");
	if(days > 0 || hours > 0) intext += mkstr(hours, " час ", " часа ", " часов ");
	intext += mkstr(minutes, " минута ", " минуты ", " минут ");
	intext += mkstr(dt2, " секунда ", " секунды ", " секунд ");
	document.getElementById('times').innerText = intext;
	document.getElementById('dtd').innerText = document.getElementById('dtd').innerText - 1;
	setTimeout("refresh()", 1000);
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

function add2()
{
	var oXmlHttp = getHTTPRequestObject();
	if(oXmlHttp)
	{
		oXmlHttp.onreadystatechange = function()
		{
			if(oXmlHttp.readyState == 1)
			{
				document.getElementById("ajaxloader").style.display = "block";
			}
			if(oXmlHttp.readyState == 4)
			{
				document.getElementById("ajaxloader").style.display = "none";
				document.getElementById('email').value = "Готово";
			}
		}
		oXmlHttp.open("GET", "ajax.php?act=addadr&email="+document.getElementById('email').value, true);
		oXmlHttp.send();
	}
}

//-->
</script>
</HEAD>
<BODY BGCOLOR=#eeeeee LEFTMARGIN=0 TOPMARGIN=10 MARGINWIDTH=0 MARGINHEIGHT=0 rightmargin="0" onload="javascript: refresh();">
<TABLE height="100%" BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center">
<tr valign="middle" align="center"><td><img src="screen.jpg" border="0" alt="Второй Мир"><br>
<font color="#aaaaaa" face="Arial">До запуска проекта Второй Мир осталось <span id='times'></span></font><br><br>
<table cellpadding="0" cellspacing="0"><tr><td><font face="Arial">Напомнить мне по e-mail: </font><input id="email" type="text" maxlength="50" style="background-color: #eeeeee; border-style: solid; border-width: 1; border-color: #c82c2a;"> <input type="submit" onclick="javascript: add2();" value="OK" style="background-color: #eeeeee; border-style: solid; border-width: 1; border-color: #c82c2a;"></td>
<td width="5">&nbsp;</td><td><img id="ajaxloader" style="display: none;" src="ajax-loader.gif" border="0" alt="Подождите..."></td></tr></table>
<br><br>
<font size="3" color="#aaaaaa">Внимание! Введенный Вами адрес будет использован только один раз для напоминания о запуске проекта.<br>Адрес не будет использоваться для отправки нежелательной корреспонденции или повторных напоминаний.</font>
</td></tr>
</table>
<div id='dtd' style="display: none;"><?php print mktime(22,0,0,10,31,2008)-time(); ?></div>
</body>
</html>