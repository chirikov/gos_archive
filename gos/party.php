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
$_GET['act'] != "default"
) $_GET['act'] = "default";

$body = "";

if($_GET['act'] == "select")
{
	$q1 = mysql_query("select party, state from info where id = ".$_COOKIE['mir_id']);
	$party = mysql_result($q1, 0, 'party');
	if($party != 0)
	{
		$body .= "Внимание! Вы уже числитесь в одной из партий. Смена партии приведет к потере Ваших заслуг.<br>";
	}
	$body .= "<form name='f1' method='post' action='party.php?act=selected'>
	<select name='party'>";
	$state = mysql_result($q1, 0, 'state');
	$q2 = mysql_query("select id, name from parties where state = ".$state);
	while($arr2 = mysql_fetch_assoc($q2))
	{
		$body .= "<option value=".$arr2['id'];
		if($party == $arr2['id']) $body .= " selected";
		$body .= ">".$arr2['name']."<br>";
	}
	$body .= "</select><input type='submit' value='Выбрать'></form>";
}
elseif($_GET['act'] == "selected")
{
	$q2 = mysql_query("update info set party = ".$_POST['party']." where id = ".$_COOKIE['mir_id']);
	header("Location: profile.php?act=home");
	exit;
}
elseif($_GET['act'] == "default")
{
	$q1 = mysql_query("select name, details from parties where id = ".$_GET['pid']);
	$row = mysql_fetch_assoc($q1);
	$body .= $row['name']."<br><br>".$row['details'];
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>