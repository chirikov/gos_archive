<?php

include_once("inc/my_connect.php");
include_once("inc/control.php");

if(loginned() !== true)
{
	header("Location: login.php?act=login");
	exit;
}
$q = mysql_query("update users set lasttime = ". time() ." where id = ".$_COOKIE['mir_id']);

function loadform()
{
	return "
	<table align='center'>
	<form action='photo.php?act=loadedavatar' method='post' name='f1' enctype='multipart/form-data'>
	<input type='hidden' name='MAX_FILE_SIZE' value='2097152'>
	<tr><td align='right'>Фотография:</td><td><input type='file' name='photo'>(< 2Мб)</td></tr>
	<tr><td></td><td><input type='submit' name='go' value='Загрузить'></td></tr>
	</form></table>";	
}
function newalbumform()
{
	return "
	<table align='center'>
	<form action='photo.php?act=newedalbum' method='post' name='f1' enctype='multipart/form-data'>
	<tr><td align='right'>Название альбома:</td><td><input type='text' name='name' maxlength=100></td></tr>
	<tr><td align='right'>Загрузить фотографии:</td><td><input type='hidden' name='MAX_FILE_SIZE' value='2097152'></td></tr>
	<tr><td></td><td><input type='file' name='photo1'></td></tr>
	<tr><td></td><td><input type='file' name='photo2'></td></tr>
	<tr><td></td><td><input type='file' name='photo3'></td></tr>
	<tr><td></td><td><input type='submit' name='go' value='Создать'></td></tr>
	</form></table>";
}
function addphotoform($aid)
{
	return "
	<table align='center'>
	<form action='photo.php?act=addedphoto' method='post' name='f1' enctype='multipart/form-data'>
	<input type='hidden' name='aid' value='".$aid."'>
	<tr><td align='right'></td><td><input type='hidden' name='MAX_FILE_SIZE' value='2097152'></td></tr>
	<tr><td></td><td><input type='file' name='photo1'></td></tr>
	<tr><td></td><td><input type='file' name='photo2'></td></tr>
	<tr><td></td><td><input type='file' name='photo3'></td></tr>
	<tr><td></td><td><input type='submit' name='go' value='Загрузить'></td></tr>
	</form></table>";
}

if(
$_GET['act'] != "loadavatar" &&
$_GET['act'] != "loadedavatar" &&
$_GET['act'] != "albums" &&
$_GET['act'] != "newalbum" &&
$_GET['act'] != "newedalbum" &&
$_GET['act'] != "view" &&
$_GET['act'] != "delalbum" &&
$_GET['act'] != "delphoto" &&
$_GET['act'] != "addphoto" &&
$_GET['act'] != "addedphoto" &&
$_GET['act'] != "album"
) $_GET['act'] = "albums";

$body = "";

if($_GET['act'] == "loadavatar")
{
	$q0 = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
	$row0 = mysql_fetch_assoc($q0);
	$body .= "<a href='profile.php?act=home'>".$row0['name']." ".$row0['surname']."</a> - <font class='b'>Смена фотографии</font><br><br>";
	$body .= loadform();
}
elseif($_GET['act'] == "loadedavatar")
{
	if($_FILES['photo']['size'] == 0)
	{
		header("Location: photo.php?act=loadavatar");
		exit;
	}
	else
	{
		if($_FILES['photo']['type'] != "image/jpeg" && $_FILES['photo']['type'] != "image/pjpeg")
		{
			$q0 = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
			$row0 = mysql_fetch_assoc($q0);
			$body .= "<a href='profile.php?act=home'>".$row0['name']." ".$row0['surname']."</a> - <font class='b'>Смена фотографии</font><br><br>";
			$body .= "<font color='red'>Поддерживаются файлы только в формате JPEG.</font><br><br>".loadform();
		}
		else
		{
			if($_FILES['photo']['size'] > 2097152)
			{
				$q0 = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
				$row0 = mysql_fetch_assoc($q0);
				$body .= "<a href='profile.php?act=view&uid=1'>".$row0['name']." ".$row0['surname']."</a> - <font class='b'>Смена фотографии</font><br><br>";
				$body .= "<font color='red'>Вы указали слишком большой файл.</font><br><br>".loadform();
			}
			else
			{
				$q1 = mysql_query("select avatar from users where id = ".$_COOKIE['mir_id']);
				unlink("photos/".$_COOKIE['mir_id']."/".mysql_result($q1, 0).".jpg");
				unlink("photos/".$_COOKIE['mir_id']."/".mysql_result($q1, 0)."s.jpg");
				$ar = range('a', 'z');
				shuffle($ar);
				$code = substr(implode("", $ar), rand(0, 19), 7);
				while(file_exists("photos/".$_COOKIE['mir_id']."/".$code.".jpg"))
				{
					$code = substr(implode("", $ar), rand(0, 19), 7);
				}
				move_uploaded_file($_FILES['photo']['tmp_name'], "photos/".$_COOKIE['mir_id']."/".$code.".jpg");
				$sizes = getimagesize("photos/".$_COOKIE['mir_id']."/".$code.".jpg");
				$nh = 200;
				$nw = 150;
				$hr = $sizes[1]/$nh;
				$wr = $sizes[0]/$nw;
				if($wr >= $hr)
				{
					$w = $nw;
					$h = $sizes[1]/$wr;
				}
				else
				{
					$h = $nh;
					$w = $sizes[0]/$hr;
				}
				$im = imagecreatefromjpeg("photos/".$_COOKIE['mir_id']."/".$code.".jpg");
				$im2 = imagecreatetruecolor($w, $h);
				$im3 = imagecreatetruecolor($w/2, $h/2);
				imagecopyresampled($im2, $im, 0, 0, 0, 0, $w, $h, imagesx($im), imagesy($im));
				imagecopyresampled($im3, $im, 0, 0, 0, 0, $w/2, $h/2, imagesx($im), imagesy($im));
				imagedestroy($im);
				imagejpeg($im2, "photos/".$_COOKIE['mir_id']."/".$code.".jpg");
				imagejpeg($im3, "photos/".$_COOKIE['mir_id']."/".$code."s.jpg");
				imagedestroy($im2);
				imagedestroy($im3);
				$q = mysql_query("update users set avatar = '".$code."' where id = ".$_COOKIE['mir_id']);
				header("Location: profile.php?act=home");
				exit;
			}
		}
	}
}
elseif($_GET['act'] == "albums")
{
	if($_GET['uid'] < 1) $_GET['uid'] = $_COOKIE['mir_id'];
	$q0 = mysql_query("select name, surname from users where id = ".$_GET['uid']);
	$row0 = mysql_fetch_assoc($q0);
	$body .= "<a href='profile.php?act=view&uid=".$_GET['uid']."'>".$row0['name']." ".$row0['surname']."</a> - <font class='b'>Альбомы</font><br><br>";
	if($_GET['uid'] == $_COOKIE['mir_id']) $body .= "<a href='photo.php?act=newalbum'>Создать альбом</a><br><br>";
	$q1 = mysql_query("select id, name, addtime from albums where uid = ".$_GET['uid']." order by addtime desc");
	if(mysql_num_rows($q1)>0)
	{
		$body .= "<table>";
		while($row = mysql_fetch_assoc($q1))
		{
			$q2 = mysql_query("select code from photos where album = ".$row['id']." order by addtime desc");
			$pn = mysql_num_rows($q2);
			$body .= "<tr><td><a href='photo.php?act=album&aid=".$row['id']."'>";
			if($pn > 0) $body .= "<img border='0' src='photos/".$_GET['uid']."/".$row['id']."/".mysql_result($q2, 0)."s.jpg'>";
			else $body .= "<img border='0' src='img/nophoto.jpg'>";
			$body .= "</a></td><td width='100%' valign='top'><a href='photo.php?act=album&aid=".$row['id']."'>".$row['name']."</a><br><br>
			Добавлен: ".date("d.m.Y", $row['addtime'])."<br>
			фотографий: ".$pn."
			</td></tr>";
		}
		$body .= "</table><br>";
	}
}
elseif($_GET['act'] == "newalbum")
{
	$q0 = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
	$row0 = mysql_fetch_assoc($q0);
	$body .= "<a href='profile.php?act=home'>".$row0['name']." ".$row0['surname']."</a> - <font class='b'>Новый Альбом</font><br><br>";
	$body .= newalbumform();
}
elseif($_GET['act'] == "newedalbum")
{
	$name = trim($_POST['name']);
	if(strlen($name) < 1)
	{
		$q0 = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
		$row0 = mysql_fetch_assoc($q0);
		$body .= "<a href='profile.php?act=home'>".$row0['name']." ".$row0['surname']."</a> - <font class='b'>Новый Альбом</font><br><br>";
		$body .= "<font color='red'>Вы не ввели название альбома.</font><br><br>".newalbumform();
	}
	else
	{
		$q1 = mysql_query("insert into albums (uid, name, addtime) values ('".$_COOKIE['mir_id']."', '".addslashes(htmlspecialchars($name))."', '".time()."')");
		$aid = mysql_insert_id($mysql);
		mkdir("photos/".$_COOKIE['mir_id']."/".$aid, 0644);
		for($i=1; $i<=3; $i++)
		{
			if($_FILES['photo'.$i]['size'] != 0 && ($_FILES['photo'.$i]['type'] == "image/jpeg" || $_FILES['photo']['type'] != "image/pjpeg") && $_FILES['photo'.$i]['size'] <= 2097152)
			{
				$ar = range('a', 'z');
				shuffle($ar);
				$code = substr(implode("", $ar), rand(0, 19), 7);
				while(file_exists("photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg"))
				{
					$code = substr(implode("", $ar), rand(0, 19), 7);
				}
				move_uploaded_file($_FILES['photo'.$i]['tmp_name'], "photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg");
				$sizes = getimagesize("photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg");
				$nh = 390;
				$nw = 520;
				$hr = $sizes[1]/$nh;
				$wr = $sizes[0]/$nw;
				if($wr >= $hr)
				{
					$w = $nw;
					$h = $sizes[1]/$wr;
				}
				else
				{
					$h = $nh;
					$w = $sizes[0]/$hr;
				}
				$im = imagecreatefromjpeg("photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg");
				$im2 = imagecreatetruecolor($w, $h);
				$im3 = imagecreatetruecolor($w/3, $h/3);
				imagecopyresampled($im2, $im, 0, 0, 0, 0, $w, $h, imagesx($im), imagesy($im));
				imagecopyresampled($im3, $im, 0, 0, 0, 0, $w/5, $h/5, imagesx($im), imagesy($im));
				imagedestroy($im);
				imagejpeg($im2, "photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg");
				imagejpeg($im3, "photos/".$_COOKIE['mir_id']."/".$aid."/".$code."s.jpg");
				imagedestroy($im2);
				imagedestroy($im3);
				$q = mysql_query("insert into photos (album, code, addtime) values('".$aid."', '".$code."', '".time()."')");
			}
		}
		header("Location: photo.php?act=album&aid=".$aid);
		exit;
	}
}
elseif($_GET['act'] == "addphoto")
{
	if($_GET['aid'] < 1)
	{
		header("Location: profile.php?act=home");
		exit;
	}
	$q1 = mysql_query("select name from albums where id = ".$_GET['aid']." && uid = ".$_COOKIE['mir_id']);
	if(mysql_num_rows($q1) < 1)
	{
		header("Location: profile.php?act=home");
		exit;
	}
	else
	{
		$q0 = mysql_query("select name, surname from users where id = ".$_COOKIE['mir_id']);
		$row0 = mysql_fetch_assoc($q0);
		$row1 = mysql_fetch_assoc($q1);
		$body .= "<a href='profile.php?act=home'>".$row0['name']." ".$row0['surname']."</a> - <a href='photo.php?act=album&aid=".$_GET['aid']."'>".$row1['name']."</a> - <font class='b'>Добавление фотографий</font><br><br>";
		$body .= addphotoform($_GET['aid']);
	}
}
elseif($_GET['act'] == "addedphoto")
{
	$aid = $_POST['aid'];
	$q1 = mysql_query("select name from albums where id = ".$aid." && uid = ".$_COOKIE['mir_id']);
	if($aid < 1)
	{
		header("Location: profile.php?act=home");
		exit;
	}
	elseif(mysql_num_rows($q1) < 1)
	{
		header("Location: profile.php?act=home");
		exit;
	}
	else
	{
		for($i=1; $i<=3; $i++)
		{
			if($_FILES['photo'.$i]['size'] != 0 && ($_FILES['photo'.$i]['type'] == "image/jpeg" || $_FILES['photo']['type'] != "image/pjpeg") && $_FILES['photo'.$i]['size'] <= 2097152)
			{
				$ar = range('a', 'z');
				shuffle($ar);
				$code = substr(implode("", $ar), rand(0, 19), 7);
				while(file_exists("photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg"))
				{
					$code = substr(implode("", $ar), rand(0, 19), 7);
				}
				move_uploaded_file($_FILES['photo'.$i]['tmp_name'], "photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg");
				$sizes = getimagesize("photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg");
				$nh = 390;
				$nw = 520;
				$hr = $sizes[1]/$nh;
				$wr = $sizes[0]/$nw;
				if($wr >= $hr)
				{
					$w = $nw;
					$h = $sizes[1]/$wr;
				}
				else
				{
					$h = $nh;
					$w = $sizes[0]/$hr;
				}
				$im = imagecreatefromjpeg("photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg");
				$im2 = imagecreatetruecolor($w, $h);
				$im3 = imagecreatetruecolor($w/5, $h/5);
				imagecopyresampled($im2, $im, 0, 0, 0, 0, $w, $h, imagesx($im), imagesy($im));
				imagecopyresampled($im3, $im, 0, 0, 0, 0, $w/5, $h/5, imagesx($im), imagesy($im));
				imagedestroy($im);
				imagejpeg($im2, "photos/".$_COOKIE['mir_id']."/".$aid."/".$code.".jpg");
				imagejpeg($im3, "photos/".$_COOKIE['mir_id']."/".$aid."/".$code."s.jpg");
				imagedestroy($im2);
				imagedestroy($im3);
				$q = mysql_query("insert into photos (album, code, addtime) values('".$aid."', '".$code."', '".time()."')");
			}
		}
		header("Location: photo.php?act=album&aid=".$aid);
		exit;
	}
}
elseif($_GET['act'] == "album")
{
	if($_GET['aid'] < 1)
	{
		$q1 = mysql_query("select id from albums where uid = ".$_COOKIE['mir_id']." limit 1");
		$_GET['aid'] = mysql_result($q1, 0);
	}
	$q1 = mysql_query("select uid from albums where id = ".$_GET['aid']);
	$uid = mysql_result($q1, 0);
	$q2 = mysql_query("select name from albums where id = ".$_GET['aid']);
	$q0 = mysql_query("select name, surname from users where id = ".$uid);
	$row0 = mysql_fetch_assoc($q0);
	$body .= "<a href='profile.php?act=view&uid=".$uid."'>".$row0['name']." ".$row0['surname']."</a> - <a href='photo.php?act=albums&uid=".$uid."'>Альбомы</a> - <font class='b'>".mysql_result($q2, 0)."</font><br><br>";
	$q3 = mysql_query("select id, code from photos where album = ".$_GET['aid']);
	$q4 = mysql_query("select id from albums where id = ".$_GET['aid']." and uid = ".$_COOKIE['mir_id']);
	if(mysql_num_rows($q4) == 1)
	{
		$body .= "<a href='photo.php?act=addphoto&aid=".$_GET['aid']."'>Добавить фотографии</a> <a href='photo.php?act=delalbum&aid=".$_GET['aid']."' onclick='javascript:
		var a = confirm(\"Удалить альбом?\");
		if(!a) return false;
		'>Удалить альбом</a><br><br>";
	}
	if(mysql_num_rows($q3) < 1)
	{
		$body .= "В этом альбоме нет фотографий.<br>";
	}
	else
	{
		$body .= "<table cellspacing=20>";
		$r = 1;
		$c = 1;
		while($row = mysql_fetch_assoc($q3))
		{
			if($c == 5) $c = 1;
			if($c == 1) $body .= "<tr>";
			$body .= "<td><a href='photo.php?act=view&pid=".$row['id']."'><img border='0' src='photos/".$uid."/".$_GET['aid']."/".$row['code']."s.jpg'></a></td>";
			if($c == 4)
			{
				$body .= "</tr>";
				$r++;
			}
			$c++;
		}
		$body .= "</table><br>";
	}
}
elseif($_GET['act'] == "view")
{
	if($_GET['pid'] < 1)
	{
		$q1 = mysql_query("select id from albums where uid = ".$_COOKIE['mir_id']." limit 1");
		$aid = mysql_result($q1, 0);
		$q2 = mysql_query("select id from photos where album = ".$aid." limit 1");
		$_GET['pid'] = mysql_result($q2);
	}
	$q4 = mysql_query("select album, name, code, addtime from photos where id = ".$_GET['pid']);
	$row = mysql_fetch_assoc($q4);
	$q3 = mysql_query("select uid, name from albums where id = ".$row['album']);
	$row3 = mysql_fetch_assoc($q3);
	$q0 = mysql_query("select name, surname from users where id = ".$row3['uid']);
	$row0 = mysql_fetch_assoc($q0);
	$body .= "<a href='profile.php?act=view&uid=".$row3['uid']."'>".$row0['name']." ".$row0['surname']."</a> - <a href='photo.php?act=albums&uid=".$row3['uid']."'>Альбомы</a> - <a href='photo.php?act=album&aid=".$row['album']."'>".$row3['name']."</a><br><br>
	<table align='center'>
	<tr><td><img id='scene' src='photos/".$row3['uid']."/".$row['album']."/".$row['code'].".jpg'></td></tr>";
	if($row3['uid'] == $_COOKIE['mir_id']) $body .= "<tr><td><a id='delhref' href='photo.php?act=delphoto&pid=".$_GET['pid']."' onclick='javascript:
	var a = confirm(\"Удалить фотографию?\");
	if(!a) return false;
	'>Удалить фотографию</a><br><br></td></tr>";
	$body .= "<tr><td>Другие фотографии из альбома:<br><table width='100%'><tr>";
	$q5 = mysql_query("select id, code from photos where album = ".$row['album']);
	$pnum = mysql_num_rows($q5);
	$n = 0;
	if($pnum > 4) $body .= "<td width='15'><img id='imgl' onclick='javascript: clickleft();' style='cursor: hand;' src='img/lfar.gif'></td>";
	else $body .= "<td width='15'><img id='imgl' onclick='javascript: clickleft();' style='cursor: hand; display: none;' src='img/lfar.gif'></td>";
	if($pnum > 1)
	{
		$body .= "<td width='25%' align='center'><img onclick='javascript: imclick(1);' id='sm1'></td>";
		$body .= "<td width='25%' align='center'><img onclick='javascript: imclick(2);' id='sm2'></td>";
		$n = 2;
		if($pnum > 2)
		{
			$body .= "<td width='25%' align='center'><img onclick='javascript: imclick(3);' id='sm3'></td>";
			$n = 3;
			if($pnum > 3)
			{
				$body .= "<td width='25%' align='center'><img onclick='javascript: imclick(4);' id='sm4'></td>";
				$n = 4;
			}
		}
	}
	if($pnum > 4) $body .= "<td width='15'><img id='imgr' onclick='javascript: clickright();' style='cursor: hand;' src='img/rgar.gif'></td>";
	else $body .= "<td width='15'><img id='imgr' onclick='javascript: clickright();' style='cursor: hand; display: none;' src='img/rgar.gif'></td>";
	$body .= "</tr></table>";
	if($n > 0)
	{
		$body .= "
		<script language='javascript' type='text/javascript'>
		<!--//
		var ph = new Array();
		var phid = new Array();
		var stack = new Array();
		var last = ".$pnum.";";
		for($i=1; $i<=$pnum; $i++)
		{
			$code = mysql_result($q5, $i-1, 'code');
			$body .= "ph[".$i."] = '".$code."';";
			$body .= "phid[".$i."] = '".mysql_result($q5, $i-1, 'id')."';";
			if($code == $row['code']) $pid = $i;
		}
		if($pid == 1)
		{
			$body .= "document.getElementById('sm1').src = 'photos/".$row3['uid']."/".$row['album']."/".$row['code']."s.jpg'; stack[1] = ".$pid.";";
			for($i = 2; $i<=$n; $i++)
			{
				$body .= "document.getElementById('sm".$i."').src = 'photos/".$row3['uid']."/".$row['album']."/".mysql_result($q5, $i-1, 'code')."s.jpg'; stack[".$i."] = ".$i.";";
			}
		}
		elseif($pid == $pnum)
		{
			$body .= "document.getElementById('sm".$n."').src = 'photos/".$row3['uid']."/".$row['album']."/".$row['code']."s.jpg'; stack[".$n."] = ".$pid.";";
			for($i = $n-1; $i>=1; $i--)
			{
				$body .= "document.getElementById('sm".$i."').src = 'photos/".$row3['uid']."/".$row['album']."/".mysql_result($q5, $pnum-$n+$i-1, 'code')."s.jpg'; stack[".$i."] = ".($pnum-$n+$i).";";
			}
		}
		else
		{
			if($pid == $pnum-1 && $n > 3)
			{
				$body .= "document.getElementById('sm3').src = 'photos/".$row3['uid']."/".$row['album']."/".$row['code']."s.jpg'; stack[3] = ".$pid.";";
				$body .= "document.getElementById('sm4').src = 'photos/".$row3['uid']."/".$row['album']."/".mysql_result($q5, $pid, 'code')."s.jpg'; stack[4] = ".($pid+1).";";
				for($i = 1; $i<=2; $i++)
				{
					$body .= "document.getElementById('sm".$i."').src = 'photos/".$row3['uid']."/".$row['album']."/".mysql_result($q5, $pid+$i-4, 'code')."s.jpg'; stack[".$i."] = ".($pid+$i-3).";";
				}
			}
			else
			{
				$body .= "document.getElementById('sm2').src = 'photos/".$row3['uid']."/".$row['album']."/".$row['code']."s.jpg'; stack[2] = ".$pid.";";
				$body .= "document.getElementById('sm1').src = 'photos/".$row3['uid']."/".$row['album']."/".mysql_result($q5, $pid-2, 'code')."s.jpg'; stack[1] = ".($pid-1).";";
				for($i = 3; $i<=$n; $i++)
				{
					$body .= "document.getElementById('sm".$i."').src = 'photos/".$row3['uid']."/".$row['album']."/".mysql_result($q5, $pid+$i-3, 'code')."s.jpg'; stack[".$i."] = ".($pid+$i-2).";";
				}
			}
		}
		if($pid == 1 || $pid == 2) $body .= "document.getElementById('imgl').style.display = 'none';";
		if($pid == $pnum || $pid == $pnum-1 || $pid == $pnum-2) $body .= "document.getElementById('imgr').style.display = 'none';";
		$body .= "
		
		function imclick(no)
		{
			document.getElementById('scene').src = 'photos/".$row3['uid']."/".$row['album']."/'+ph[stack[no]]+'.jpg';
			document.getElementById('delhref').href = 'photo.php?act=delphoto&pid='+phid[stack[no]];
		}
		function clickright()
		{
		document.getElementById('imgl').style.display = 'block';";
		for($i = 1; $i<=3; $i++)
		{
			$body .= "document.getElementById('sm".$i."').src = document.getElementById('sm".($i+1)."').src; stack[".$i."] = stack[".($i+1)."];";
		}
		$body .= "document.getElementById('sm4').src = 'photos/".$row3['uid']."/".$row['album']."/'+ph[stack[4]+1]+'s.jpg'; stack[4] = stack[4]+1;";
		$body .= "
		if(stack[4] == last) {document.getElementById('imgr').style.display = 'none';}
		}
		function clickleft()
		{
		document.getElementById('imgr').style.display = 'block';";
		for($i = 4; $i>=2; $i--)
		{
			$body .= "document.getElementById('sm".$i."').src = document.getElementById('sm".($i-1)."').src; stack[".$i."] = stack[".($i-1)."];";
		}
		$body .= "document.getElementById('sm1').src = 'photos/".$row3['uid']."/".$row['album']."/'+ph[stack[1]-1]+'s.jpg'; stack[1] = stack[1]-1;";
		$body .= "
		if(stack[1] == 1) {document.getElementById('imgl').style.display = 'none';}
		}
		//-->
		</script>";
	}
	$body .= "</td></tr>
	</table>";
}
elseif($_GET['act'] == "delalbum")
{
	if($_GET['aid'] < 1)
	{
		header("Location: photo.php?act=albums");
		exit;
	}
	else
	{
		$q1 = mysql_query("select id from albums where id = ".$_GET['aid']." and uid = ".$_COOKIE['mir_id']);
		if(mysql_num_rows($q1) == 0)
		{
			header("Location: photo.php?act=albums");
			exit;
		}
		else
		{
			$q2 = mysql_query("select code from photos where album = ".$_GET['aid']);
			while($row = mysql_fetch_assoc($q2))
			{
				unlink("photos/".$_COOKIE['mir_id']."/".$_GET['aid']."/".$row['code'].".jpg");
				unlink("photos/".$_COOKIE['mir_id']."/".$_GET['aid']."/".$row['code']."s.jpg");
			}
			rmdir("photos/".$_COOKIE['mir_id']."/".$_GET['aid']);
			$q3 = mysql_query("delete from photos where album = ".$_GET['aid']);
			$q4 = mysql_query("delete from albums where id = ".$_GET['aid']);
		}
		header("Location: photo.php?act=albums");
		exit;
	}
}
elseif($_GET['act'] == "delphoto")
{
	if($_GET['pid'] < 1)
	{
		header("Location: photo.php?act=albums");
		exit;
	}
	else
	{
		$q1 = mysql_query("select album from photos where id = ".$_GET['pid']);
		$aid = mysql_result($q1, 0);
		$q4 = mysql_query("select id from albums where id = ".$aid." and uid = ".$_COOKIE['mir_id']);
		if(mysql_num_rows($q4) == 0)
		{
			header("Location: photo.php?act=albums");
			exit;
		}
		else
		{
			$q2 = mysql_query("select code from photos where id = ".$_GET['pid']);
			$row = mysql_fetch_assoc($q2);
			unlink("photos/".$_COOKIE['mir_id']."/".$aid."/".$row['code'].".jpg");
			unlink("photos/".$_COOKIE['mir_id']."/".$aid."/".$row['code']."s.jpg");
			$q3 = mysql_query("delete from photos where id = ".$_GET['pid']);
		}
		header("Location: photo.php?act=album&aid=".$aid);
		exit;
	}
}

include_once("inc/head.php");
print $body;
include_once("inc/foot.php");
?>