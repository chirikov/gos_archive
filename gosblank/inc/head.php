<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$qs = mysql_query("select id from messages where recepient = ".$_COOKIE['mir_id']." and seen = 0");
$snum = mysql_num_rows($qs);
?>


<a href="index.php">Главная</a>
<a href="login.php?act=logout">Выйти</a>


<a href="contacts.php?act=deault">Контакты</a><br>
<a href="mail.php?act=inlist">Сообщения <span id='leftmesnum'><?php if($snum > 0) print " [".$snum."]"; ?></span></a><br>
<a href="photo.php?act=albums">Фотографии</a><br>
