</td>
	<?php
	//if(ereg("index.php", $PHP_SELF)) require_once("right_part.php");
	if(THIS_PAGE=="recettes") require_once("right_part_gastro.php");
	elseif(THIS_PAGE!="recettes" && THIS_PAGE!="account" && THIS_PAGE!="cart" && THIS_PAGE!="checkout") require_once("right_part.php");
	?>
	<td width="10" background="images/bckg_line_right.gif"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
</tr>
</table>
<table width="798" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
	<td background="images/bckg_line_left.gif"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
	<td height="1" background="images/pointillets.gif"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
	<td background="images/bckg_line_right.gif"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
</tr>
<tr>
	<td background="images/bckg_line_left.gif"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
	<td valign="top" align="center" style="width:100%;<?php if (!isset($_GET["impr"])){ ?>background-color:#FEFCE7;<?php } ?>">
	<br>&copy; Château Val Joanis 2006 - Tous droits réservés<br>
	Le Club Chancel - Château Val Joanis - 84120 Pertuis
	<br>Tél : 04 90 79 20 77 - Fax : 04 90 09 69 52 - eMail : <a href="mailto:info@clubchancel.com">info@clubchancel.com</a><br><br>
	</td>
	<td background="images/bckg_line_right.gif"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
</tr>
<tr>
	<td colspan="3" background="images/bckg_line_bottom.gif"><img src="images/blank.gif" width="10" height="10" border="0" alt=""></td>
</tr>
</table>
<div align="center" class="small"><a href="http://www.vinternet.net" target="_blank">VINTERNET</a> - <a href="#" onClick="window.open('mentions.php','_blank','toolbar=0, location=0, directories=0, status=0, scrollbars=0, resizable=0, copyhistory=0, menuBar=0, width=600, height=295,left=0,top=0');return(false)">Mentions légales</a> - L'abus d'alcool est dangereux pour la santé<br><br></div>

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
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-312822-3";
urchinTracker();
</script>
</BODY>
</HTML>
