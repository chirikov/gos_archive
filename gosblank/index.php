<?php
include_once("inc/my_connect.php");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

<script language="javascript" type="text/javascript" src="inc/indexajax.js"></script>

<body>

<a href="" onclick="javascript: main(); return false;">�������</a>
<a href="" onclick="javascript: register(); return false;">�����������</a>
<a href="" onclick="javascript: activate(); return false;">���������</a><br><br>

<table style="display: none;" id="tableresult"><tr><td id="tdresult"></td></tr></table><br><br>

<div id="main" <?php if($_GET['act'] != "") print "style='display: none;'"; ?>>
	����� ���������� �� ������ ���!<br>
	��� ����� ������� ���� E-mail � ������ (�� ��, ��� � ��� ����� �� ���� ���������):<br><br>
	<form action="login.php?act=loginapi" method="POST" name="flogin">
	E-mail:<input type="Text" name="email" maxlength="50">
	������:<input type="password" name="pass" maxlength="50">
	<input type="Submit" name="go" value="�����">
	</form>
</div>
<div id="register" <?php if($_GET['act'] == "register") print "style='display: block;'"; else print "style='display: none;'"; ?>>
	<?php
	$retv = '
	<table align="center">
	<form action="javascript: regdone();" method="POST" name="f1">
	<tr><td align="right">�������:</td><td><input type="Text" name="surname" maxlength="40"></td></tr>
	<tr><td align="right">���:</td><td><input type="Text" name="name" maxlength="40"></td></tr>
	<tr><td align="right">��������:</td><td><input type="Text" name="lastname" maxlength="40"></td></tr>
	<tr><td align="right">E-mail:</td><td><input type="Text" name="email" maxlength="50"></td></tr>
	<tr><td align="right">������:</td><td><input type="password" name="pass" maxlength="50"> (>5 ��������)</td></tr>
	<tr><td align="right">����������� ������:</td><td><input type="password" name="pass2" maxlength="50"></td></tr>
	<tr><td align="right">���:</td><td><select name="sex">
	<option value="m">���.
	<option value="f">���.
	</select></td></tr>
	<tr><td align="right">���� ��������:</td><td>&nbsp;</td></tr>
	<tr><td align="right">����:</td><td><select name="day" id="selday">';
	for($i=1; $i<=31; $i++)
	{
		$retv .= '<option id="opt'.$i.'" value="'.$i.'">'.$i;
	}
	$retv .= '</select></td></tr>
	<tr><td align="right">�����:</td><td><select name="month" onchange="javascript: selchg();">';
	$mons = array("@", "������", "�������", "����", "������", "���", "����", "����", "������", "��������", "�������", "������", "�������");
	for($i=1; $i<=12; $i++)
	{
		$retv .= '<option value="'.$i.'">'.$mons[$i];
	}
	$retv .= '</select></td></tr>
	<tr><td align="right">���:</td><td><select name="year" id="selyear" onchange="javascript: selchg();">';
	for($i=1998; $i>=1920; $i--)
	{
		$retv .= '<option value="'.$i.'">'.$i;
	}
	$retv .= '</select></td></tr>
	<tr><td align="right"><input type="Submit" name="go" value="���������"></td><td><input type="reset" value="��������" name="res"></td></tr>
	</form>
	</table>'; 
	print $retv;
	?>
</div>
<div id="activate" <?php if($_GET['act'] == "activate") print "style='display: block;'"; else print "style='display: none;'"; ?>>
	<table align="center">
	<form action="javascript: actdone();" method="POST" name="f2">
	<tr><td align="right">E-mail:</td><td><input type="Text" name="email" maxlength="50"><a href="" onclick="javascript: sendactcode(); return false;">��������� ������ � ����� ��� ���</a></td></tr>
	<tr><td align="right">��� ���������:</td><td><input type="Text" name="actcode" maxlength="6"></td></tr>
	<tr><td>&nbsp;</td><td><input type="Submit" name="go" value="���������"></td></tr>
	</form>
	</table>
</div>

<br>��������� ����: 
	<?php
	$q = mysql_query("select count(*) from users");
	print mysql_result($q, 0);
	?>
	<br>��-����: 
	<?php
	$q = mysql_query("select id from users where lasttime > ".(time()-5*60));
	$num_online = mysql_num_rows($q);
	print $num_online;
	?>
</body>
