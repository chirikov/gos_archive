<?php

$dbhost = "78.108.81.244";
$dbuser = "u29794";
$dbpass = "221397";

$mysql = @mysql_connect($dbhost, $dbuser, $dbpass);
@mysql_select_db("b29794", $mysql);

if($_GET['act'] == "addadr")
{
	$email = iconv('UTF-8', 'windows-1251', trim($_GET['email']));
	$q = mysql_query("insert into startremind (email) values ('".addslashes(htmlspecialchars($email))."')");
}

?>