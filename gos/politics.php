<?php

include_once("inc/my_connect.php");
include_once("inc/control.php");

if(loginned() !== true)
{
	header("Location: login.php?act=login");
	exit;
}
$q = mysql_query("update users set lasttime = ". time() ." where id = ".$_COOKIE['mir_id']);

if($_GET['act'] != "default" && $_GET['act'] != "") $_GET['act'] = "default";

$body = "";

if($_GET['act'] == "default")
{
	$q1 = mysql_query("select party from info where id = ".$_COOKIE['mir_id']);
	$party = mysql_result($q1, 0);
	if($party == 0)
	{
		$body .= "�� �� �������� �� � ����� �� ������. <a href='party.php?act=select'>��������</a>";
	}
	else
	{
		$q2 = mysql_query("select name from parties where id = ".$party);
		$body .= "���� ������ - <a href='party.php?act=default&pid=".$party."'>".mysql_result($q2, 0)."</a>";
	}
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>