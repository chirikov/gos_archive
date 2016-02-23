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
$_GET['act'] != "inlist" && 
$_GET['act'] != "outlist" && 
$_GET['act'] != "inread" && 
$_GET['act'] != "outread" && 
$_GET['act'] != "write" && 
$_GET['act'] != "sendajax" && 
$_GET['act'] != "checkajax" && 
$_GET['act'] != "send" && 
$_GET['act'] != "seen" && 
$_GET['act'] != "delete"
) $_GET['act'] = "inlist";

$body = "";

if($_GET['act'] == "inlist")
{
	$q0 = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
	$row0 = mysql_fetch_assoc($q0);
	$body .= "<div class='path'><a href='profile.php?act=home'>".$row0['name']." ".$row0['surname']."</a> - Сообщения</div><br>";
	$q1 = mysql_query("select id, sender, theme, time, seen from messages where recepient = ".$_COOKIE['mir_id']." order by time desc");
	$body .= "Входящие <a href='mail.php?act=outlist'>Исходящие</a><br>";
	if(mysql_num_rows($q1) == 0)
	{
		$body .= "У Вас нет входящих сообщений.";
	}
	else
	{
		$body .= "<table border=0>
		<tr><td><b>От</td><td><b>Тема</td><td><b>Получено</td></tr>";
		while($row = mysql_fetch_assoc($q1))
		{
			$q2 = mysql_query("select name, surname from users where id = ".$row['sender']);
			$row2 = mysql_fetch_assoc($q2);
			if($row['seen'] == 0) $body .= "<tr style='color: #000000;'><td>";
			else $body .= "<tr style='color: #888888;'><td>";
			$body .= $row2['name']." ".$row2['surname']."</td><td>";
			if($row['seen'] == 0) $body .= "<a style='color: #000000;' ";
			else $body .= "<a style='color: #888888;' ";
			$body .= "href='mail.php?act=inread&mid=".$row['id']."'>".$row['theme']."</a></td><td>".date("d.m.Y, H:i", $row['time'])." <a href='mail.php?act=delete&mid=".$row['id']."'>удалить</a></td></tr>";
		}
		$body .= "</table>";
	}
}
elseif($_GET['act'] == "outlist")
{
	$q0 = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
	$row0 = mysql_fetch_assoc($q0);
	$body .= "<div class='path'><a href='profile.php?act=home'>".$row0['name']." ".$row0['surname']."</a> - Сообщения</div><br>";
	$q1 = mysql_query("select id, recepient, theme, time, seen from messages where sender = ".$_COOKIE['mir_id']." order by time desc");
	$body .= "<a href='mail.php?act=inlist'>Входящие</a> Исходящие<br>";
	if(mysql_num_rows($q1) == 0)
	{
		$body .= "У Вас нет исходящих сообщений.";
	}
	else
	{
		$body .= "<table border=0>
		<tr><td><b>Кому</td><td><b>Тема</td><td><b>Отправлено</td></tr>";
		while($row = mysql_fetch_assoc($q1))
		{
			$q2 = mysql_query("select name, surname from users where id = ".$row['recepient']);
			$row2 = mysql_fetch_assoc($q2);
			if($row['seen'] == 0) $body .= "<tr style='color: #000000;'><td>";
			else $body .= "<tr style='color: #888888;'><td>";
			$body .= $row2['name']." ".$row2['surname']."</td><td>";
			if($row['seen'] == 0) $body .= "<a style='color: #000000;' ";
			else $body .= "<a style='color: #888888;' ";
			$body .= "href='mail.php?act=outread&mid=".$row['id']."'>".$row['theme']."</a></td><td>".date("d.m.Y, H:i", $row['time'])."</td></tr>";
		}
		$body .= "</table>";
	}
}
elseif($_GET['act'] == "inread")
{
	$q0 = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
	$row0 = mysql_fetch_assoc($q0);
	$body .= "<div class='path'><a href='profile.php?act=home'>".$row0['name']." ".$row0['surname']."</a> - Сообщения</div><br>";
	$q1 = mysql_query("select sender, theme, text from messages where id = ".$_GET['mid']);
	$row = mysql_fetch_assoc($q1);
	$q2 = mysql_query("select name, surname from users where id = ".$row['sender']);
	$row2 = mysql_fetch_assoc($q2);
	$body .= "От: ".$row2['name']." ".$row2['surname']."<br>
	Тема: ".$row['theme']."<br>
	Сообщение: ".$row['text']."<br><br>
	<a href='mail.php?act=write&recepient=".$row['sender']."'>Ответить</a>";
	$q3 = mysql_query("update messages set seen = 1 where id = ".$_GET['mid']);
}
elseif($_GET['act'] == "outread")
{
	$q0 = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
	$row0 = mysql_fetch_assoc($q0);
	$body .= "<div class='path'><a href='profile.php?act=home'>".$row0['name']." ".$row0['surname']."</a> - Сообщения</div><br>";
	$q1 = mysql_query("select sender, theme, text from messages where id = ".$_GET['mid']);
	$row = mysql_fetch_assoc($q1);
	$q2 = mysql_query("select name, surname from users where id = ".$row['sender']);
	$row2 = mysql_fetch_assoc($q2);
	$body .= "Кому: ".$row2['name']." ".$row2['surname']."<br>
	Тема: ".$row['theme']."<br>
	Сообщение: ".$row['text'];
}
elseif($_GET['act'] == "write")
{
	$q0 = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
	$row0 = mysql_fetch_assoc($q0);
	$body .= "<div class='path'><a href='profile.php?act=home'>".$row0['name']." ".$row0['surname']."</a> - Новое сообщение</div><br>";
	$q1 = mysql_query("select name, surname from users where id = ".$_GET['recepient']);
	$row = mysql_fetch_assoc($q1);
	$body .= "<form action='mail.php?act=send' method='post' name='f1'>
	<input type='hidden' name='recepient' value=".$_GET['recepient'].">
	Кому: ".$row['name']." ".$row['surname']."<br>
	Тема: <input type='text' name='theme' maxlength='255'><br>
	Текст: <textarea name='text'></textarea><br>
	<input type='submit' name='go' value='Отправить'>
	</form>";
}
elseif($_GET['act'] == "sendajax")
{
	$theme = iconv('UTF-8', 'windows-1251', trim($_POST['theme']));
	$text = iconv('UTF-8', 'windows-1251', trim($_POST['text']));
	if(strlen($theme) > 0 || strlen($text) > 0)
	{
		$q2 = mysql_query("insert into messages (sender, recepient, theme, text, time) values ('".$_COOKIE['mir_id']."', '".$_POST['recepient']."', '".addslashes(htmlspecialchars($theme))."', '".addslashes(htmlspecialchars($text))."', '".time()."')");
	}
	exit;
}
elseif($_GET['act'] == "checkajax")
{
	$q1 = mysql_query("select id, sender, theme, text from messages where seen = 0 && recepient = ".$_COOKIE['mir_id']." order by time desc");
	$ns = mysql_num_rows($q1);
	print $ns;
	while($row = mysql_fetch_assoc($q1))
	{
		$q9 = mysql_query("select name, surname from users where id = ".$row['sender']);
		$row3 = mysql_fetch_assoc($q9);
		print ";|/".$row3['name']."&nbsp;".$row3['surname'].";|/".$row['theme'].";|/".$row['text'].";|/".$row['sender'].";|/".$row['id'];
	}
	exit;
}
elseif($_GET['act'] == "send")
{
	$theme = trim($_POST['theme']);
	$text = trim($_POST['text']);
	if(strlen($theme) > 0 || strlen($text) > 0)
	{
		$q2 = mysql_query("insert into messages (sender, recepient, theme, text, time) values ('".$_COOKIE['mir_id']."', '".$_POST['recepient']."', '".addslashes(htmlspecialchars($theme))."', '".addslashes(htmlspecialchars($text))."', '".time()."')");
	}
	header("Location: profile.php?act=home");
	exit;
}
elseif($_GET['act'] == "delete")
{
	if($_GET['mid'] < 0)
	{
		header("Location: profile.php?act=home");
		exit;
	}
	$q1 = mysql_query("select id from messages where id = ".$_GET['mid']." && recepient = ".$_COOKIE['mir_id']);
	if(mysql_num_rows($q1) == 1)
	{
		$q2 = mysql_query("delete from messages where id = ".$_GET['mid']);
		header("Location: mail.php?act=inlist");
		exit;
	}
	else
	{
		header("Location: profile.php?act=home");
		exit;
	}
}
elseif($_GET['act'] == "seen")
{
	if($_GET['mid'] < 0)
	{
		header("Location: profile.php?act=home");
		exit;
	}
	$q1 = mysql_query("select id from messages where id = ".$_GET['mid']." && recepient = ".$_COOKIE['mir_id']);
	if(mysql_num_rows($q1) == 1)
	{
		$q2 = mysql_query("update messages set seen = 1 where id = ".$_GET['mid']);
	}
	else
	{
		header("Location: profile.php?act=home");
		exit;
	}
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>