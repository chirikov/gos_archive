<?php

include_once("inc/my_connect.php");
include_once("inc/control.php");

if(loginned() !== true)
{
	header("Location: index.php");
	exit;
}
$q = mysql_query("update users set lasttime = ". time() ." where id = ".$_COOKIE['mir_id']);

if(
$_GET['act'] != "home" && 
$_GET['act'] != "view"
) $_GET['act'] = "home";

$body = "";

if($_GET['act'] == "home")
{
	$q1 = mysql_query("select name, surname, lastname, birthdate, avatar from users where id = ".$_COOKIE['mir_id']);
	$row1 = mysql_fetch_assoc($q1);
	
	$q1 = mysql_query("select state, city, street, house from info where id = ".$_COOKIE['mir_id']);
	$row2 = mysql_fetch_assoc($q2);
	$q3 = mysql_query("select name from states where id = ".$row2['state']);
	$statename = mysql_result($q3, 0);
	$q4 = mysql_query("select name from cities where id = ".$row2['city']);
	$cityname = mysql_result($q4, 0);
	$q5 = mysql_query("select name from streets where id = ".$row2['street']);
	$streetname = mysql_result($q5, 0);
	$body .= '
	<script language="javascript" type="text/javascript" src="inc/profileajax.js"></script>
	<a href="photo.php?act=loadavatar" title="Сменить фотографию"><img border=0 src="photos/'.$_COOKIE['mir_id'].'/'.$row1['avatar'].'.jpg"></a><br>
	'.$row1['surname']." ".$row1['name']." ".$row1['lastname'].'<br>
	Дата рождения: '.date("d.m.Y", $row1['birthdate']).'<br>
	Адрес: '.$statename.', г. '.$row2['city'].', '.$streetname.' '.$row2['house'];

###########
	$q8 = mysql_query("select id, sender, theme, text from messages where seen = 0 && recepient = ".$_COOKIE['mir_id']." order by time desc");
	
		$body .= "<table width='100%' border=0 cellpadding=0 cellspacing=0><tr><td width='100%' class='b'>Новые&nbsp;сообщения:</td><td><img id='uajaxloader' src='img/ajax-loader.gif' style='display: none;'></td><td><input style='border-style: none;' checked type='checkbox' id='auto'>автоматическое&nbsp;обновление</td></tr></table>
		<div id='divmes'><table id='messages' border='1' cellspacing='0' cellpadding='3' style='border-style: solid; border-color: #c82c2a; border-width: 1' width='100%'>";
		
		$i=1;
		while($row = mysql_fetch_assoc($q8))
		{
			$q9 = mysql_query("select name, surname from users where id = ".$row['sender']);
			$row3 = mysql_fetch_assoc($q9);
			$body .= "
			<tr id='tr".$i."' style='cursor: default;' onmouseover='javascript: this.bgColor = red;' onmouseout='javascript: this.bgColor = white;'>
			<td width='100' valign='top'>".$row3['name']."&nbsp;".$row3['surname']."</td>
			<td width='100%'>Тема: ".$row['theme']."<br>
			<div align='left' id='tablemes".$i."' style='display: none;'>
			Текст: ".$row['text']."<br>
			<a id='href".$i."' href='#' onclick='javascript: showform(".$i.");'>Ответить</a> <a href='#' onclick='javascript: 
			var a = confirm(\"Удалить сообщение?\");
			if(a) delmes(".$i.", ".$row['id'].");
			return false;
			'>Удалить</a>
			</div>
			<table align='left' id='tablean".$i."' style='display: none;'>
			<form id='form".$i."' action='javascript: sendmes(".$i.");' method='get'>
			<input type='hidden' name='recepient' value='".$row['sender']."'>
			<tr><td align='right'>Тема:</td><td><input type='text' name='theme' value=`".$row['theme']."` maxlength='100'></td></tr>
			<tr><td align='right' valign='top'>Текст:</td><td><textarea name='text' style='background-color: #eeeeee;' rows='4' cols='30'></textarea></td></tr>
			<tr><td></td><td><input type='submit' name='go' value='Отправить'> <img id='ajaxloader".$i."' src='img/ajax-loader.gif' style='display: none;'></td></tr>
			</form>
			</table>
			</td><td valign='top' onclick='javascript: timer = arrowclick(".$i.", ".$row['id'].", timer);'><img src='img/dnar.gif' id='img".$i."'></td></tr>";
			$i++;
		}
		if($i == 1) $body .= "<tr><td width='100%' align='center'>Новых сообщений нет.</td></tr>";
		$body .= "</table></div><img src='img/upar.gif' style='display: none;'><div id='opened' style='display: none;'>0</div><br>";
###########
	$q9 = mysql_query("select id, name from albums where uid = ".$_COOKIE['mir_id']." limit 3");
	$i = 0;
	$arr = array();
	while($row4 = mysql_fetch_assoc($q9))
	{
		$q91 = mysql_query("select id from photos where album = ".$row4['id']." limit 1");
		if(mysql_num_rows($q91) > 0)
		{
			$i++;
			$arr[$i] = $row4;
		}
	}
	$na = $i;
	if($na > 0)
	{
		$body .= "Альбомы:<br>
		<table><tr>";
		for($i=1; $i<=$na; $i++)
		{
			$body .= "<td><a href='photo.php?act=album&aid=".$arr[$i]['id']."'>".$arr[$i]['name']."</a></td>";
		}
		$body .= "</tr><tr>";
		for($i=1; $i<=$na; $i++)
		{
			$q10 = mysql_query("select code from photos where album = ".$arr[$i]['id']." order by addtime desc limit 1");
			$body .= "<td style='border-style: none;'><a href='photo.php?act=album&aid=".$arr[$i]['id']."'><img border=0 src='photos/".$_COOKIE['mir_id']."/".$arr[$i]['id']."/".mysql_result($q10, 0)."s.jpg'></a></td>";
		}
		$body .= '</tr></table>';
	}
###########
}
elseif($_GET['act'] == "view")
{
	if($_GET['uid'] < 1) $_GET['uid'] = $_COOKIE['mir_id'];
	if($_GET['uid'] == $_COOKIE['mir_id'])
	{
		header("Location: profile.php?act=home");
		exit;
	}
	
	$q1 = mysql_query("select name, surname, lastname, birthdate, avatar from users where id = ".$_COOKIE['mir_id']);
	$row1 = mysql_fetch_assoc($q1);
	$q1 = mysql_query("select state, city, street, house from info where id = ".$_COOKIE['mir_id']);
	$row2 = mysql_fetch_assoc($q2);
	$q3 = mysql_query("select name from states where id = ".$row2['state']);
	$statename = mysql_result($q3, 0);
	$q4 = mysql_query("select name from cities where id = ".$row2['city']);
	$cityname = mysql_result($q4, 0);
	$q5 = mysql_query("select name from streets where id = ".$row2['street']);
	$streetname = mysql_result($q5, 0);
	$body .= '
	<script language="javascript" type="text/javascript" src="inc/profileajax.js"></script>
	<img border=0 src="photos/'.$_COOKIE['mir_id'].'/'.$row1['avatar'].'.jpg"><br>
	'.$row1['surname']." ".$row1['name']." ".$row1['lastname'].'<br>
	Дата рождения: '.date("d.m.Y", $row1['birthdate']).'<br>
	Адрес: '.$statename.', г. '.$row2['city'].', '.$streetname.' '.$row2['house'];
	
	$body .= "<a href='mail.php?act=write&recepient=".$_GET['uid']."'>Написать сообщение</a><br>";
	$q8 = mysql_query("select id from contacts where id = '".$_COOKIE['mir_id']."' and cid = '".$_GET['uid']."'");
	if(mysql_num_rows($q8)<1)
	{
		$body .= " <a href='contacts.php?act=add&cid=".$_GET['uid']."'>Добавить в контакты</a><br>";
	}
	$body .= "<br>";
	
	
	$q9 = mysql_query("select id, name from albums where uid = ".$_GET['uid']." limit 3");
	$i = 0;
	$arr = array();
	while($row4 = mysql_fetch_assoc($q9))
	{
		$q91 = mysql_query("select id from photos where album = ".$row4['id']." limit 1");
		if(mysql_num_rows($q91) > 0)
		{
			$i++;
			$arr[$i] = $row4;
		}
	}
	$na = $i;
	if($na > 0)
	{
		$body .= "Альбомы:<br>
		<table><tr>";
		for($i=1; $i<=$na; $i++)
		{
			$body .= "<td><a href='photo.php?act=album&aid=".$arr[$i]['id']."'>".$arr[$i]['name']."</a></td>";
		}
		$body .= "</tr><tr>";
		for($i=1; $i<=$na; $i++)
		{
			$q10 = mysql_query("select code from photos where album = ".$arr[$i]['id']." order by addtime desc limit 1");
			$body .= "<td style='border-style: none;'><a href='photo.php?act=album&aid=".$arr[$i]['id']."'><img border=0 src='photos/".$_GET['uid']."/".$arr[$i]['id']."/".mysql_result($q10, 0)."s.jpg'></a></td>";
		}
		$body .= '</tr></table>';
	}
}


include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>