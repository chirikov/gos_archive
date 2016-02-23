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
$_GET['act'] != "selected" && 
$_GET['act'] != "selectworkplace" && 
$_GET['act'] != "selectliveplace" && 
$_GET['act'] != "default" && 
$_GET['act'] != "selectedliveplace"
) $_GET['act'] = "default";

$body = "";

if($_GET['act'] == "select")
{
	$q1 = mysql_query("select city, state from info where id = ".$_COOKIE['mir_id']);
	$row = mysql_fetch_assoc($q1);
	$q3 = mysql_query("select name from states where id = ".$row['state']);
	$body .= "<div class='path'><a href='state.php'>".mysql_result($q3, 0)."</a> - Выбор города</div>";
	$city = $row['city'];
	if($city != 0)
	{
		$body .= "Внимание! Вы уже проживаете в одном из городов. Смена города приведет к потере работы.<br>";
	}
	$body .= "
	<form name='f1' method='post' action='city.php?act=selected'>";
	$q2 = mysql_query("select id, name from cities where state = ".$row['state']);
	while($arr2 = mysql_fetch_assoc($q2))
	{
		$body .= "<input type='radio' name='city' value=".$arr2['id'];
		if($city == $arr2['id']) $body .= " checked";
		$body .= ">".$arr2['name']."<br>";
	}
	$body .= "<input type='submit' value='Выбрать'></form>";
}
elseif($_GET['act'] == "selected")
{
	$q2 = mysql_query("update info set city = ".$_POST['city'].", work = 0, party = 0, live = 0 where id = ".$_COOKIE['mir_id']);
	header("Location: profile.php?act=home");
	exit;
}
elseif($_GET['act'] == "selectworkplace")
{
	$q1 = mysql_query("select city, work from info where id = ".$_COOKIE['mir_id']);
	$row = mysql_fetch_assoc($q1);
	$q3 = mysql_query("select name from cities where id = ".$row['city']);
	$body .= "<div class='path'><a href='city.php?act=default&cid=".$row['city']."'>".mysql_result($q3, 0)."</a> - Выбор работы</div>";
	$workplace = $row['work'];
	if($workplace != 0)
	{
		$body .= "Внимание! Вы уже работаете в одном из учреждений. Смена учреждения приведет к потере прежней работы.<br>";
	}
	$body .= "
	<form name='f1' method='post' action='object.php?act=selectjob'>";
	$q2 = mysql_query("select id, name from objects where city = ".$row['city']);
	$body .= "<select name='workplace'>";
	while($arr2 = mysql_fetch_assoc($q2))
	{
		$body .= "<option value=".$arr2['id'];
		if($workplace == $arr2['id']) $body .= " selected";
		$body .= ">".$arr2['name']."<br>";
	}
	$body .= "</select><input type='submit' value='Выбрать'></form>";
}
elseif($_GET['act'] == "selectliveplace")
{
	$q = mysql_query("select city from info where id = ".$_COOKIE['mir_id']);
	$city = mysql_result($q, 0);
	$q3 = mysql_query("select name from cities where id = ".$city);
	$body .= "<div class='path'><a href='city.php?act=default&cid=".$city."'>".mysql_result($q3, 0)."</a> - Выбор места жительства</div>";
	$q1 = mysql_query("select id, name from lives where city = ".$city);
	$body .= "<form name='f1' method='post' action='city.php?act=selectedliveplace'>";
	while($row = mysql_fetch_assoc($q1))
	{
		$q2 = mysql_query("select id, room, price from liveprices where lid = ".$row['id']);
		$body .= "<b>".$row['name']."</b><br>";
		while($row2 = mysql_fetch_assoc($q2))
		{
			$body .= "<input type='radio' name='live' value='".$row2['id']."'>".$row2['room']." - ".$row2['price']."<br>";
		}
	}
	$body .= "<input type='submit' value='Купить'></form>";
}
elseif($_GET['act'] == "selectedliveplace")
{
	$q2 = mysql_query("update info set live = ".$_POST['live']." where id = ".$_COOKIE['mir_id']);
	header("Location: profile.php?act=home");
	exit;
}
elseif($_GET['act'] == "default")
{
	if($_GET['cid'] < 1) {
		$q = mysql_query("select city from info where id = ".$_COOKIE['mir_id']);
		$citydef = mysql_result($q, 0);
		$city = $citydef;
	}
	else $city = $_GET['cid'];
	$q = mysql_query("select state from info where id = ".$_COOKIE['mir_id']);
	$state = mysql_result($q, 0);
	$q2 = mysql_query("select name from cities where id = ".$city);
	$cityname = mysql_result($q2, 0);
	$q3 = mysql_query("select name from states where id = ".$state);
	$body .= "<div class='path'><a href='state.php'>".mysql_result($q3, 0)."</a> - ".$cityname."</div>";
	$q1 = mysql_query("select id, name from objects where city = ".$city);
	
	$body .= "<br><br>Объекты:<br>";
	while($row = mysql_fetch_assoc($q1))
	{
		$body .= "<a href='object.php?act=default&oid=".$row['id']."'>".$row['name']."</a><br>";
	}
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>