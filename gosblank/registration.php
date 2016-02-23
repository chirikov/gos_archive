<?php
include_once("inc/my_connect.php");

if(
$_GET['act'] != "ajaxregdone" && 
$_GET['act'] != "ajaxsendcode" && 
$_GET['act'] != "ajaxactdone"
) exit;


if($_GET['act'] == "ajaxregdone")
{
	$surname = iconv('UTF-8', 'windows-1251', trim(substr($_POST['surname'], 0, 40)));
	$name = iconv('UTF-8', 'windows-1251', trim(substr($_POST['name'], 0, 40)));
	$lastname = iconv('UTF-8', 'windows-1251', trim(substr($_POST['lastname'], 0, 40)));
	$email = iconv('UTF-8', 'windows-1251', trim(substr($_POST['email'], 0, 50)));
	$pass = iconv('UTF-8', 'windows-1251', trim(substr($_POST['pass'], 0, 50)));
	$pass2 = iconv('UTF-8', 'windows-1251', trim(substr($_POST['pass2'], 0, 50)));
	if(strlen($surname)<1 or strlen($name)<1 or strlen($email)<6 or strlen($pass)<6 or $pass != $pass2)
	{
		if(strlen($surname)<1) $result = "esurname";
		elseif(strlen($name)<1) $result = "ename";
		elseif(strlen($email)<6) $result = "eemail";
		elseif(strlen($pass)<6) $result = "epass";
		elseif($pass != $pass2) $result = "epassdifferent";
	}
	else
	{
		$q4 = mysql_query("select id from users where email = '".$email."'");
		$regd = mysql_num_rows($q4);
		if($regd > 0) $result = "eemailexists";
		else
		{
			$birth = mktime(0,0,0, $_POST['month'], $_POST['day'], $_POST['year']);
			$actcode = rand(100000, 999999);
			$q = mysql_query("insert into users (surname, name, lastname, email, pass, birthdate, sex, avatar, actcode, lasttime, regtime) values ('".addslashes(htmlspecialchars($surname))."', '".addslashes(htmlspecialchars($name))."', '".addslashes(htmlspecialchars($lastname))."', '".addslashes(htmlspecialchars($email))."', '".md5($pass)."', '".$birth."', '".$_POST['sex']."', '', '".$actcode."', '".time()."', '".time()."')");
			if($q) {
				@mail($email, "Второй Мир. Код активации.", "
				\tЗдравствуйте, ".$name.".\n\n
				Ваш код активации - ".$actcode."\n\n
				Для активации Вы можете открыть следующую ссылку:\n
				<a href='http://2ndworld.ru/registration.php?act=actdone&email=".$email."&actcode=".$actcode."'>http://2ndworld.ru/registration.php?act=actdone&email=".$email."&actcode=".$actcode."</a>");
				$result = "ok";
			}
			else
			{
				$result = "ereg";
			}
		}
	}
	print $result;
}
elseif($_GET['act'] == "ajaxactdone")
{
	$email = trim(substr($_GET['email'], 0, 50));
	$q1 = mysql_query("select actcode from users where email = '".$email."'");
	if(mysql_num_rows($q1) < 1) $result = "eemail";
	else
	{
		$codedb = mysql_result($q1, 0);
		if($codedb != 0)
		{
			if($codedb != $_GET['actcode'])
			{
				$result = "ecode";
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
				$result = "ok";
			}
		}
		else
		{
			$result = "eemailacted";
		}
	}
	print $result;
}
elseif($_GET['act'] == "ajaxsendcode")
{
	$email = trim(substr($_GET['email'], 0, 50));
	$q1 = mysql_query("select actcode from users where email = '".$email."'");
	if(mysql_num_rows($q1) < 1) $result = "eemail";
	else
	{
		$codedb = mysql_result($q1, 0);
		if($codedb != 0)
		{
			@mail($email, "Второй Мир. Код активации.", "
			\tЗдравствуйте, ".$name.".\n\n
			Ваш код активации - ".$actcode."\n\n
			Для активации Вы можете открыть следующую ссылку:\n
			<a href='http://2ndworld.ru/registration.php?act=actdone&email=".$email."&actcode=".$actcode."'>http://2ndworld.ru/registration.php?act=actdone&email=".$email."&actcode=".$actcode."</a>");
			$result = "ok";
		}
		else
		{
			$result = "eemailacted";
		}
	}
	print $result;
}
?>