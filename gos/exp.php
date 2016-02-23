<html>



<input type="text" style="background-color: #ffffff; border-style: solid; border-width: 1; border-color: #cc0000;"><br>
<select style="background-color: #ffffff; border-style: solid; border-width: 1; border-color: #cc0000;"><option>1111111111111111</select>



<table id="table1" height="1" BORDER=1 style="border-style: none;" cellpadding="0" cellspacing="0" onblur="javascript: 
close();
">
<tr>
<td id="el01" valign="middle" width="80" height="18" style="border-style: solid; border-right-style: none; border-color: #cc0000;">111</td><td id="el02" align="center" width="18" style="border-style: solid; border-left-style: solid; border-color: #cc0000;" onclick="javascript:
document.getElementById('el1').style.display = 'block';
document.getElementById('el2').style.display = 'block';
"><img src="img/select.gif"></td>
</tr>
<tr onmouseover="javascript: this.style.backgroundColor = '#ff0000';" onmouseout="javascript: this.style.backgroundColor = '#ffffff';" onclick="javascript:
document.getElementById('el01').innerText = this.children(0).innerText;
close();
"><td width="80" id="el1" colspan="2" style="display: none; border-style: solid; border-top-style: none; border-bottom-style: none; border-color: #cc0000;">111</td></tr>
<tr onmouseover="javascript: this.style.backgroundColor = '#ff0000';" onmouseout="javascript: this.style.backgroundColor = '#ffffff';" onclick="javascript:
document.getElementById('el01').innerText = this.children(0).innerText;
close();
"><td width="80" id="el2" colspan="2" style="display: none; border-style: solid; border-top-style: none; border-bottom-style: solid; border-color: #cc0000;">222</td></tr>
</table>


<script language="javascript" type="text/javascript">
<!--//
	function close()
	{
		document.getElementById('el1').style.display = 'none';
		document.getElementById('el2').style.display = 'none';
	}
-->
</script>
</html>