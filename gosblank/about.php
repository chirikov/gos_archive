<?php
include_once("inc/my_connect.php");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<a href="about.php">О проекте</a>
<a href="feedback.php">Связаться</a>
<a href="registration.php?act=reg">Регистрация</a><br>
<a href="registration.php?act=act">Активация</a><br>

<a href='index.php'>Мир</a> - О проекте<br>
<b>Мир</b> - новый Интернет-проект, позволяющий
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