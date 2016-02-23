<?php
include_once("inc/my_connect.php");
include_once("inc/control.php");

if(
$_GET['act'] != "ajaxlogindone" && 
$_GET['act'] != "loginapi" && 
$_GET['act'] != "logout"
) exit;

if($_GET['act'] == "ajaxlogindone")
{
	$email = trim($_POST['email']);
	$pass = trim($_POST['pass']);
	$q1 = mysql_query("select id, pass, actcode from users where email = '".$email."'");
	if(mysql_num_rows($q1) < 1) $result = "eemail";
	else
	{
		$id = mysql_result($q1, 0, 'id');
		$passdb = mysql_result($q1, 0, 'pass');
		$actcode = mysql_result($q1, 0, 'actcode');
		if($actcode != 0)
		{
			$result = "eemailunactive";
		}
		elseif($passdb != md5($pass))
		{
			$result = "epass";
		}
		else
		{
			setcookie("mir_id", $id, time()+60*60*24*2);
			setcookie("mir_logged", $passdb, time()+60*60*24*2);
			$result = "ok";
		}
	}
	print $result;
}
elseif($_GET['act'] == "loginapi")
{
	$ch = curl_init("http://login.userapi.com/auth?login=force&site=1768&email=".$_POST['email']."&pass=".$_POST['pass']);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 1);
	$head = curl_exec($ch);
	curl_close($ch);
	$sid = substr($head, strpos($head, "Location: http://2ndworld.ru/index.html#;sid=")+45, 32);
	if(substr($sid, 0, 1) == "-")
	{
		header("Location: index.php?loginerror=1");
		exit;
	}
	else
	{
		header("Location: profile.php?sid=".$sid);
		exit;
	}
}
elseif($_GET['act'] == "logout")
{
	$q = mysql_query("update users set lasttime = ".(time()-5*60)." where id = ".$_COOKIE['mir_id']);
	setcookie("mir_id", "", time()-3600);
	setcookie("mir_logged", "", time()-3600);
	header("Location: index.php");
	exit;
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>