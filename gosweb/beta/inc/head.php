<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$qs = mysql_query("select id from messages where recepient = ".$_COOKIE['mir_id']." and seen = 0");
$snum = mysql_num_rows($qs);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Мир</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<meta http-equiv="Description" content="Онлайн игра Мир">
<meta http-equiv="Keywords" content="Мир, политика, экономика, общество, виртуальный">
<style type="text/css"><!--
.path {font-family: Tahoma, sans-serif; font-size: 15 px; color:#333333; padding-left: 10; font-weight: bold}
.b {font-family: Tahoma, sans-serif; font-size: 12 px; color:#333333; font-weight: bold}
td.tmain {border-style: none; border-bottom-style: solid; border-color: #aaaaaa; border-width: 2; padding-left: 10 px; padding-top: 10 px; padding-right: 10 px}
td.topnav a {font-family: Tahoma, sans-serif; font-size: 12 px; color:#333333; padding-left: 2; font-weight: bold}
td.topnav a:hover {font-family: Tahoma, sans-serif; font-size: 12 px; color:#333333; padding-left: 2; font-weight: bold}
li {list-style : square; color: #aaaaaa; }
td {font-family: Tahoma, sans-serif; font-size: 14px; color: #333333;}
td a {text-decoration: none; color:#c82c2a; font-family: Tahoma, sans-serif; font-size: 12 px; padding-left: 2; font-weight: bold}
td a:hover {text-decoration: underline; color:#c82c2a; font-family: Tahoma, sans-serif; font-size: 12 px; padding-left: 2; font-weight: bold}
input {background-color: #eeeeee; border-style: solid; border-width: 1; border-color: #c82c2a}
td.leftmenu {border-style: none; border-right-style: solid; border-top-style: solid; border-color: #aaaaaa; border-width: 2; padding-top: 10 px; padding-left: 10 px;}
td.leftmenu a {text-decoration: none; color:#c82c2a; font-family: Tahoma, sans-serif; font-size: 12 px; padding-left: 2; font-weight: bold}
td.leftmenu a:hover {text-decoration: underline; color:#c82c2a; font-family: Tahoma, sans-serif; font-size: 12 px; padding-left: 2; font-weight: bold}
--></style>
</HEAD>
<BODY BGCOLOR=#FFFFFF LEFTMARGIN=0 TOPMARGIN=10 MARGINWIDTH=0 MARGINHEIGHT=0 rightmargin="0">
<TABLE WIDTH=780 BORDER=1 style="border-style: none; font-family: Tahoma, sans-serif; font-size: 14px; color: #333333;" CELLPADDING=0 CELLSPACING=0 bgcolor="#eeeeee" align="center" height="100%">
<tr>
<td width="200" style="border-style: none;" valign="top" rowspan="2"><a href="profile.php"><img border="0" src="img/logo.jpg"></a></td><td style="height: 85; border-style: none;">&nbsp;</td>
</tr>
<tr>
<td style="height: 15; border-style: none; border-left-style: solid; border-bottom-style: solid; border-color: #aaaaaa; border-width: 2;">
<table width="100%">
	<tr>
		<td width="33%" class='topnav'><a href="index.php">Главная</a></td>
		<td width="34%" align="center"><input disabled type="text" name="word" value="Поиск" maxlength="30" onfocus="javascript: if(this.value == 'Поиск') {this.value = '';}" onblur="javascript: if(this.value == '') {this.value= 'Поиск';}"></td>
		<td width="33%" align="right" class='topnav'><a href="login.php?act=logout">Выйти</a></td>
	</tr>
</table>
</td>
</tr>
<tr valign="top">
<td class="leftmenu" height="0">
<ul>
<li><font style="font-family: Tahoma, sans-serif; font-size: 12 px; color:#aaaaaa; font-weight: bold">Политика</font><br>
<li><font style="font-family: Tahoma, sans-serif; font-size: 12 px; color:#aaaaaa; font-weight: bold">Бизнес</font><br>
<li><font style="font-family: Tahoma, sans-serif; font-size: 12 px; color:#aaaaaa; font-weight: bold">Новости</font><br>
<li><a href="contacts.php?act=deault">Контакты</a><br>
<li><a href="mail.php?act=inlist">Сообщения <span id='leftmesnum'><?php if($snum > 0) print " [".$snum."]"; ?></span></a><br>
<li><font style="font-family: Tahoma, sans-serif; font-size: 12 px; color:#aaaaaa; font-weight: bold">Органайзер</font><br>
<li><a href="photo.php?act=albums">Фотографии</a><br>
</ul>
</td>
<td rowspan="2" class="tmain">