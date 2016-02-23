<?php
include_once("inc/my_connect.php");

function regform($surname="", $name="", $lastname="", $email="", $sex="m", $day="", $month="", $year="1988")
{
	$retv = '
	<script language="javascript" type="text/javascript">
	<!--//
		function selchg()
		{
		if(f1.month.value == 2)
		{
			document.getElementById(\'selday\').remove(30);
			document.getElementById(\'selday\').remove(29);
			if(document.getElementById(\'selyear\').value % 4 != 0)
			{
				document.getElementById(\'selday\').remove(28);
			}
			else
			{
				if(document.getElementById(\'selday\').options.length == 28)
				{
					var elem = document.createElement(\'option\');
					elem.id = \'opt29\';
					elem.value = \'29\';
					elem.text = \'29\';
					document.getElementById(\'selday\').add(elem, 28);
				}
			}
		}
		if(f1.month.value == 4 || f1.month.value == 6 || f1.month.value == 9 || f1.month.value == 11)
		{
			if(document.getElementById(\'selday\').options.length == 28)
			{
				var elem = document.createElement(\'option\');
				elem.id = \'opt29\';
				elem.value = \'29\';
				elem.text = \'29\';
				document.getElementById(\'selday\').add(elem, 28);
			}
			if(document.getElementById(\'selday\').options.length == 29)
			{
				var elem = document.createElement(\'option\');
				elem.id = \'opt30\';
				elem.value = \'30\';
				elem.text = \'30\';
				document.getElementById(\'selday\').add(elem, 29);
			}
			document.getElementById(\'selday\').remove(30);
		}
		if(f1.month.value == 1 || f1.month.value == 3 || f1.month.value == 5 || f1.month.value == 7 || f1.month.value == 8 || f1.month.value == 10 || f1.month.value == 12)
		{
			if(document.getElementById(\'selday\').options.length == 28)
			{
				var elem = document.createElement(\'option\');
				elem.id = \'opt29\';
				elem.value = \'29\';
				elem.text = \'29\';
				document.getElementById(\'selday\').add(elem, 28);
			}
			if(document.getElementById(\'selday\').options.length == 29)
			{
				var elem = document.createElement(\'option\');
				elem.id = \'opt30\';
				elem.value = \'30\';
				elem.text = \'30\';
				document.getElementById(\'selday\').add(elem, 29);
			}
			if(document.getElementById(\'selday\').options.length == 30)
			{
				var elem = document.createElement(\'option\');
				elem.id = \'opt31\';
				elem.value = \'31\';
				elem.text = \'31\';
				document.getElementById(\'selday\').add(elem, 30);
			}
		}
	}
	//-->
	</script>
	<table align="center">
	<form action="registration.php?act=regdone" method="POST" name="f1">
	<tr><td align="right">Фамилия:</td><td><input type="Text" name="surname" maxlength="40" value="'.$surname.'"></td></tr>
	<tr><td align="right">Имя:</td><td><input type="Text" name="name" maxlength="40" value="'.$name.'"></td></tr>
	<tr><td align="right">Отчество:</td><td><input type="Text" name="lastname" maxlength="40" value="'.$lastname.'"></td></tr>
	<tr><td align="right">E-mail:</td><td><input type="Text" name="email" maxlength="50" value="'.$email.'"></td></tr>
	<tr><td align="right">Пароль:</td><td><input type="password" name="pass" maxlength="50"> (>5 символов)</td></tr>
	<tr><td align="right">Подтвердите пароль:</td><td><input type="password" name="pass2" maxlength="50"></td></tr>
	<tr><td align="right">Пол:</td><td><select name="sex">
	<option value="m" ';
	if($sex=="m") $retv .= 'selected ';
	$retv .= '>Муж.
	<option value="f" ';
	if($sex=="f") $retv .= 'selected ';
	$retv .= '>Жен.
	</select></td></tr>
	<tr><td align="right">Дата рождения:</td><td>&nbsp;</td></tr>
	<tr><td align="right">День:</td><td><select name="day" id="selday">';
	for($i=1; $i<=31; $i++)
	{
		$retv .= '<option id="opt'.$i.'" value="'.$i.'" ';
		if($i==$day) $retv .= 'selected ';
		$retv .= '>'.$i;
	}
	$retv .= '</select></td></tr>
	<tr><td align="right">Месяц:</td><td><select name="month" onchange="javascript: selchg();">';
	$mons = array("@", "Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь");
	for($i=1; $i<=12; $i++)
	{
		$retv .= '<option value="'.$i.'" ';
		if($i==$month) $retv .= 'selected ';
		$retv .= '>'.$mons[$i];
	}
	$retv .= '</select></td></tr>
	<tr><td align="right">Год:</td><td><select name="year" id="selyear" onchange="javascript: selchg();">';
	for($i=1998; $i>=1920; $i--)
	{
		$retv .= '<option value="'.$i.'" ';
		if($i==$year) $retv .= 'selected ';
		$retv .= '>'.$i;
	}
	$retv .= '</select></td></tr>
	<tr><td align="right"><input type="Submit" name="go" value="Отправить" onclick="javascript:
	if(f1.pass.value != f1.pass2.value) {
		alert(\'Пароли не совпадают.\');
		return false;
	}
	"></td><td>
	<input type="button" value="Очистить" name="res" onclick="javascript:
	var i;
	for(i=0; i<f1.elements.length; i++) {
		if(f1.elements(i).name != \'go\' && f1.elements(i).name != \'res\' && f1.elements(i).name != \'day\' && f1.elements(i).name != \'month\' && f1.elements(i).name != \'year\' && f1.elements(i).name != \'sex\')
		f1.elements(i).value = \'\';
	}
	return false;
	"></td></tr>
	</form>
	</table>
	<script language="javascript" type="text/javascript">
	<!--//
	selchg();
	//-->
	</script>
	';
	return $retv;
}
function actform($email)
{
	return '
	<table align="center">
	<form action="registration.php?act=actdone" method="POST" name="f2">
	<tr><td align="right">E-mail:</td><td><input type="Text" name="email" maxlength="50" value="'.$email.'"></td></tr>
	<tr><td align="right">Код активации:</td><td><input type="Text" name="actcode" maxlength="6"></td></tr>
	<tr><td>&nbsp;</td><td><input type="Submit" name="go" value="Отправить"></td></tr>
	</form>
	</table>
	';
}

if(
$_GET['act'] != "reg" && 
$_GET['act'] != "regdone" && 
$_GET['act'] != "act" && 
$_GET['act'] != "actdone"
) $_GET['act'] = "reg";

if($_GET['act'] == "reg")
{
	$body = "<a href='index.php'>Мир</a> - <font class='b'>Регистрация</font><br><br>
	Пожалуйста, корректно заполните все поля:<br><br>".regform();
}
elseif($_GET['act'] == "regdone")
{
	$surname = trim(substr($_POST['surname'], 0, 40));
	$name = trim(substr($_POST['name'], 0, 40));
	$lastname = trim(substr($_POST['lastname'], 0, 40));
	$email = trim(substr($_POST['email'], 0, 50));
	$pass = trim(substr($_POST['pass'], 0, 50));
	if(strlen($surname)<1 or strlen($name)<1 or strlen($lastname)<1 or strlen($email)<6 or strlen($pass)<6)
	{
		$body = "<a href='index.php'>Мир</a> - <font class='b'>Регистрация</font><br><br><font color='red'>Неверно введены данные.</font><br><br>";
		$body .= regform($surname, $name, $lastname, $email, $_POST['sex'], $_POST['day'], $_POST['month'], $_POST['year']);
	}
	else
	{
		$q4 = @mysql_query("select id from users where email = '".$email."'");
		$regd = @mysql_num_rows($q4);
		if($regd > 0) $body = $body = "<a href='index.php'>Мир</a> - <font class='b'>Активация</font><br><br>Такой E-mail уже зарегистрирован.";
		else
		{
			$birth = mktime(0,0,0, $_POST['month'], $_POST['day'], $_POST['year']);
			$actcode = rand(100000, 999999);
			$q = @mysql_query("insert into users (surname, name, lastname, email, pass, birthdate, sex, actcode, regtime) values ('".addslashes(htmlspecialchars($surname))."', '".addslashes(htmlspecialchars($name))."', '".addslashes(htmlspecialchars($lastname))."', '".addslashes(htmlspecialchars($email))."', '".md5(addslashes(htmlspecialchars($pass)))."', '".$birth."', '".$_POST['sex']."', '".$actcode."', '".time()."')");
			if($q) {
				@mail($email, "Код активации.", "Здравствуйте. Ваш код активации - ".$actcode);
				$body = "<a href='index.php'>Мир</a> - <font class='b'>Активация</font><br><br>
				Вы зарегистрированы. На Ваш адрес было выслано письмо с кодом активации. Введите его ниже для завершения регистрации:<br><br>";
				$body .= actform($email);
			}
			else
			{
				$body .= "<a href='index.php'>Мир</a> - <font class='b'>Регистрация</font><br><br>Ошибка регистрации.";
			}
		}
	}
}
if($_GET['act'] == "act")
{
	$body .= "<a href='index.php'>Мир</a> - <font class='b'>Активация</font><br><br>
	Пожалуйста, введите свой E-mail и код активации:<br><br>".actform();
}
elseif($_GET['act'] == "actdone")
{
	$email = trim(substr($_POST['email'], 0, 50));
	$q1 = @mysql_query("select actcode from users where email = '".$email."'");
	$codedb = @mysql_result($q1, 0);
	if($codedb != 0)
	{
		if($codedb != $_POST['actcode'])
		{
			$body = "<a href='index.php'>Мир</a> - <font class='b'>Активация</font><br><br><font color='red'>Неверный код.</font><br><br>";;
			$body .= actform($email);
		}
		else
		{
			$q2 = mysql_query("update users set actcode = 0 where email = '".$email."'");
			$q3 = mysql_query("select id, pass from users where email = '".$email."'");
			$id = mysql_result($q3, 0, 'id');
			$pass = mysql_result($q3, 0, 'pass');
			mkdir("photos/".$id, 0644);
			$q4 = mysql_query("insert into info (id) values(".$id.")");
			setcookie("mir_id", $id, time()+60*60*24*2);
			setcookie("mir_logged", $pass, time()+60*60*24*2);
			header("Location: profile.php?act=home");
			exit;
		}
	}
	else
	{
		$body = "<a href='index.php'>Мир</a> - <font class='b'>Активация</font><br><br><font color='red'>Неверный E-mail.</font><br><br>";
		$body .= actform($email);
	}
}

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Мир</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<meta http-equiv="Description" content="Онлайн игра Мир">
<meta http-equiv="Keywords" content="Мир, политика, экономика, общество, виртуальный">
<style type="text/css"><!--
.path {font-family: Tahoma, sans-serif; font-size: 15 px; color:#333333; padding-left: 10; font-weight: bold}
.b {font-family: Tahoma, sans-serif; font-size: 12 px; color:#333333; font-weight: bold}
.bg {font-family: Tahoma, sans-serif; font-size: 12 px; color:#aaaaaa; font-weight: bold}
td.tmain {border-style: none; border-bottom-style: solid; border-color: #aaaaaa; border-width: 2; padding-left: 10 px; padding-top: 10 px; padding-right: 10 px}
td.topnav a {font-family: Tahoma, sans-serif; font-size: 12 px; color:#333333; padding-left: 2; font-weight: bold}
td.topnav a:hover {font-family: Tahoma, sans-serif; font-size: 12 px; color:#333333; padding-left: 2; font-weight: bold}
li {list-style : square; color: #aaaaaa; }
td {font-family: Tahoma, sans-serif; font-size: 14px; color: #333333}
td a {text-decoration: none; color:#c82c2a; font-family: Tahoma, sans-serif; font-size: 12 px; padding-left: 2; font-weight: bold}
td a:hover {text-decoration: underline; color:#c82c2a; font-family: Tahoma, sans-serif; font-size: 12 px; padding-left: 2; font-weight: bold}
input {background-color: #eeeeee; border-style: solid; border-width: 1; border-color: #c82c2a}
td.leftmenu {border-style: none; border-right-style: solid; border-top-style: solid; border-color: #aaaaaa; border-width: 2; padding-top: 10 px; padding-left: 10 px;}
td.leftmenu a {text-decoration: none; color:#c82c2a; font-family: Tahoma, sans-serif; font-size: 12 px; padding-left: 2; font-weight: bold}
td.leftmenu a:hover {text-decoration: underline; color:#c82c2a; font-family: Tahoma, sans-serif; font-size: 12 px; padding-left: 2; font-weight: bold}
--></style>
</HEAD>
<BODY BGCOLOR=#FFFFFF LEFTMARGIN=0 TOPMARGIN=10 MARGINWIDTH=0 MARGINHEIGHT=0 rightmargin="0">
<TABLE WIDTH=780 BORDER=1 style="border-style: none; font-family: Tahoma, sans-serif; font-size: 14px; color: #333333;" CELLPADDING=0 CELLSPACING=0 bgcolor="#eeeeee" align="center" height="100%">
<tr>
<td width="200" style="border-style: none;" valign="top" rowspan="2"><a href="index.php"><img border="0" src="img/logo.jpg"></a></td><td style="height: 85; border-style: none;">&nbsp;</td>
</tr>
<tr>
<td style="height: 15; border-style: none; border-left-style: solid; border-bottom-style: solid; border-color: #aaaaaa; border-width: 2;">
<table width="100%">
	<tr>
		<td width="33%" class='topnav'><a href="about.php">О проекте</a></td>
		<td width="34%" align="center"><input disabled type="text" name="word" value="Поиск" maxlength="30" onfocus="javascript: if(this.value == 'Поиск') {this.value = '';}" onblur="javascript: if(this.value == '') {this.value= 'Поиск';}"></td>
		<td width="33%" align="right" class='topnav'><a href="feedback.php">Связаться</a></td>
	</tr>
</table>
</td>
</tr>
<tr valign="top">
<td class="leftmenu" height="0">
<ul>
<?php
if($_GET['act'] == "reg") print "<li style='color: #c82c2a'>";
else print "<li>";
?>
<a href="registration.php?act=reg">Регистрация</a><br>
<?php
if($_GET['act'] == "act" || $_GET['act'] == "regdone") print "<li style='color: #c82c2a'>";
else print "<li>";
?>
<a href="registration.php?act=act">Активация</a><br>
</ul>
</td>
<td rowspan="2" class="tmain" align="left">

<?php
print $body;
?>
<br>
</td></tr><tr><td style="border-style: none; border-top-style: solid; border-right-style: solid; border-color: #aaaaaa; border-width: 2; padding-top: 10 px; padding-left: 20 px;" valign="top" class="bg">
Население Мира: 
<?php
$q = mysql_query("select count(*) from users");
print mysql_result($q, 0);
?>
<br>Он-лайн: 
<?php
$q = mysql_query("select id from users where lasttime > ".(time()-5*60));
$num_online = mysql_num_rows($q);
print $num_online;
?>
</td></tr>
<tr><td colspan="2" height="100%" style="border-style: none;">&nbsp;</td></tr></table>
</body>
</html>