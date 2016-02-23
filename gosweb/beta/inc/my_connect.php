<?php

$dbhost = "78.108.81.244";
$dbuser = "u29794";
$dbpass = "221397";

$mysql = @mysql_connect($dbhost, $dbuser, $dbpass);
@mysql_select_db("b29794", $mysql);

return $mysql;
?>
