<?php

/*  �������� ����������� ������
$streets = file("streets.txt");
$streets2 = array();
foreach($streets as $street)
{
	$str = "1|";
	$street = trim($street);
	if($street != "" && $street != "[�������]" && strlen($street) != 1)
	{
		$fir = substr($street, 0, strpos($street, " "));
		switch($fir)
		{
			case "��.": $str .= "1"; break;
			case "���.": $str .= "2"; break;
			case "�-�.": $str .= "3"; break;
			case "������": $str .= "4"; break;
			case "��-��": $str .= "5"; break;
			case "�": $str .= "6"; break;
			case "���": $str .= "7"; break;
			case "��.": $str .= "8"; break;
		}
		$str .= "|";
		$str .= substr($street, strpos($street, " ")+1);
		$streets2[] = $str;
	}
}

$fp = fopen("streets-ufa.txt", "w");
foreach($streets2 as $street)
{
	fwrite($fp, $street."\r\n");
}
fclose($fp);
*/

/* ������� � streets
include_once("../inc/my_connect.php");

$streets = file("streets-ufa.txt");

foreach($streets as $street)
{
	$arr = explode("|", trim($street));
	$q = mysql_query("insert into streets (city, type, name) values ('".$arr[0]."', '".$arr[1]."', '".$arr[2]."')");
}
*/

?>