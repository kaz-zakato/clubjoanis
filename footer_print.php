	</td>
</tr>
</table>
<table width="650" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td valign="top" align="center" style="width:100%;<?php if (!isset($_GET["impr"])){ ?>background-color:#FEFCE7;<?php } ?>">
	<br>&copy; Ch�teau Val Joanis 2006 - Tous droits r�serv�s<br>
	Le Club Joanis - Ch�teau Val Joanis - 84120 Pertuis
	<br>T�l : 04 90 79 20 77 - Fax : 04 90 09 69 52 - eMail : <a href="mailto:info@clubchancel.com">info@clubchancel.com</a><br><br>
	L'abus d'alcool est dangereux pour la sant�<br><br>
	</td>
</tr>
</table>

<!-- DEBUG -->
<pre>
<?php
if(isset($_GET["debug"]) && $_GET["debug"]=="1"){
	print_r($_SESSION);
}else {
	print "<!--";
	print_r($_SESSION);
	print "-->";
}
?>
</pre>
<!-- DEBUG -->

</BODY>
</HTML>
