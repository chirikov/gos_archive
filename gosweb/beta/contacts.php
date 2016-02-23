<?php

include_once("inc/my_connect.php");
include_once("inc/control.php");

if(loginned() !== true)
{
	header("Location: login.php?act=login");
	exit;
}
$q = mysql_query("update users set lasttime = ". time() ." where id = ".$_COOKIE['mir_id']);

if(
$_GET['act'] != "default" && 
$_GET['act'] != "add" && 
$_GET['act'] != "delete"
) $_GET['act'] = "default";

$body = "";

if($_GET['act'] == "default")
{
	if($_GET['uid'] == "") $_GET['uid'] = $_COOKIE['mir_id'];
	$q0 = mysql_query("select name, surname from users where id = ".$_GET['uid']);
	$row0 = mysql_fetch_assoc($q0);
	$body .= "<a href='profile.php?act=view&uid=1'>".$row0['name']." ".$row0['surname']."</a> - <font style='font-size: 12 px; color:#333333; padding-left: 2; font-weight: bold'>Контакты</font> <div align='right'><a href='search.php?act=default'>Поиск</a></div><br><br>";
	$q1 = mysql_query("select cid from contacts where id = ".$_GET['uid']);
	if(mysql_num_rows($q1)<1)
	{
		$body .= "У Вас пока нет контактов.";
	}
	else
	{
		$body .= '<table cellpadding="10" cellspacing="0">';
		$i = 1;
		while($row = mysql_fetch_assoc($q1))
		{
			$q2 = mysql_query("select name, surname, avatar, lasttime from users where id = ".$row['cid']);
			$row2 = mysql_fetch_assoc($q2);
			if($i % 2 == 1) $body .= "<tr bgcolor='#e5e5e5'>";
			else $body .= "<tr>";
			$body .= "
			<td rowspan='2'><a href='profile.php?act=view&uid=".$row['cid']."'><img border='0' src='photos/".$row['cid']."/".$row2['avatar']."s.jpg'></a></td>
			<td width='100%'><a href='profile.php?act=view&uid=".$row['cid']."'>".$row2['name']." ".$row2['surname']."</a><br>
			Последний визит: ".date("d.m.Y, H:i", $row2['lasttime'])."</td></tr>";
			if($i % 2 == 1) $body .= "<tr bgcolor='#e5e5e5'>";
			else $body .= "<tr>";
			$body .= "<td align='right' valign='bottom'><a href='mail.php?act=write&recipient=".$row['cid']."'>Сообщение</a> <a href='contacts.php?act=delete&cid=".$row['cid']."' onclick='javascript: 
			var a = confirm(\"Удалить контакт?\");
			if(!a) return false;'>Удалить</a></td></tr>";
		$i++;
		}
	}
	$body .= "</table>";
}
elseif($_GET['act'] == "add")
{
	$q1 = mysql_query("select id from contacts where id = '".$_COOKIE['mir_id']."' and cid = '".$_GET['cid']."'");
	if(mysql_num_rows($q1)<1)
	{
		$q2 = mysql_query("insert into contacts (id, cid) values ('".$_COOKIE['mir_id']."', '".$_GET['cid']."')");
	}
	header("Location: contacts.php?act=default");
	exit;
}
elseif($_GET['act'] == "delete")
{
	$q1 = mysql_query("delete from contacts where id = '".$_COOKIE['mir_id']."' and cid = '".$_GET['cid']."'");
	header("Location: contacts.php?act=default");
	exit;
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>