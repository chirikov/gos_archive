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
$_GET['act'] != "select" && 
$_GET['act'] != "selected"
) $_GET['act'] = "select";

$body = "";

if($_GET['act'] == "select")
{
	$q0 = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
	$row0 = mysql_fetch_assoc($q0);
	$body .= "<div class='path'><a href='profile.php?act=home'>".$row0['name']." ".$row0['surname']."</a> - Выбор государства</div><br>";
	$q1 = mysql_query("select state from info where id = ".$_COOKIE['mir_id']);
	$state = mysql_result($q1, 0);
	if($state != 0)
	{
		$body .= "<font color='#ff0000'>Внимание! Вы уже проживаете в одном из государств. Смена государства приведет к потере работы.</font><br>";
	}
	$body .= "<table width='100%'><tr><td>
	<form name='f1' method='post' action='state.php?act=selected'>";
	$q2 = mysql_query("select id, name, form, type from states where 1");
	while($arr2 = mysql_fetch_assoc($q2))
	{
		$body .= "<input type='radio' name='state' value=".$arr2['id'];
		if($state == $arr2['id']) {$body .= " checked"; $deft = $arr2['type']; $deff = $arr2['form'];}
		$body .= " onclick='javascript:
		document.getElementById(\"type\").innerText = \"".$arr2['type']."\";
		document.getElementById(\"forma\").innerText = \"".$arr2['form']."\";
		'>".$arr2['name']."<br>";
	}
	$body .= "<input type='submit' value='Выбрать'></form></td><td valign='top'>
	Тип: <div id='type'>".$deft."</div><br>
	Форма: <div id='forma'>".$deff."</div>";
}
elseif($_GET['act'] == "selected")
{
	$q2 = mysql_query("update info set city = 0, work = 0, party = 0, live = 0, state = ".$_POST['state']." where id = ".$_COOKIE['mir_id']);
	header("Location: city.php?act=select");
	exit;
}


include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>