<?php
function loginned()
{
	if(isset($_COOKIE['mir_id']))
	{
		$q = mysql_query("select pass from users where id = ".$_COOKIE['mir_id']);
		$pass = mysql_result($q, 0);
		if($pass != $_COOKIE['mir_logged'])
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	else return false;
}
?>