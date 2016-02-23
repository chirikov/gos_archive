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
$_GET['act'] != "search"
) $_GET['act'] = "default";

$body = "";

function searchform()
{
	$retv = '
	<table>
	<form action="search.php?act=search" method="POST" name="f1">
	<tr><td align="right">Искать:</td><td><select name="where">
	<option value="city">в городе
	<option value="state">в стране
	<option value="world">в мире
	</select></td></tr>
	<tr><td align="right">Фамилия:</td><td><input type="Text" name="surname" maxlength="40" value="'.$surname.'"></td></tr>
	<tr><td align="right">Имя:</td><td><input type="Text" name="name" maxlength="40" value="'.$name.'"></td></tr>
	<tr><td align="right">Пол:</td><td><select name="sex">
	<option value="m">М
	<option value="f">Ж
	</select></td></tr>
	<tr><td colspan=2 align="center"><input type="submit" value="Искать" name="go"></td></tr>
	</form>
	</table>
	';
	return $retv;
}

if($_GET['act'] == "default")
{
	$q0 = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
	$row0 = mysql_fetch_assoc($q0);
	$body .= "<div class='path'><a href='profile.php?act=home'>".$row0['name']." ".$row0['surname']."</a> - Поиск</div><br>";
	$body .= searchform();
}
elseif($_GET['act'] == "search")
{
	$name = trim($_POST['name']);
	$surname = trim($_POST['surname']);
	if($name == "" && $surname == "")
	{
		header("Location: search.php?act=default");
		exit;
	}
	else
	{
		$q0 = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
		$row0 = mysql_fetch_assoc($q0);
		$body .= "<div class='path'><a href='profile.php?act=home'>".$row0['name']." ".$row0['surname']."</a> - Поиск - Результаты</div><br>";
		$q1 = mysql_query("select id, name, surname from users where name regexp '.*".$name.".*' and surname regexp '.*".$surname.".*'");
		if(mysql_num_rows($q1) == 0)
		{
			$body .= "Не найдено. <a href='search.php?act=default'>Новый поиск</a>";
		}
		else
		{
			while($row = mysql_fetch_assoc($q1))
			{
				$body .= "<a href='profile.php?act=view&uid=".$row['id']."'>".$row['name']." ".$row['surname']."</a><br>";
			}
		}
	}
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>