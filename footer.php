</td>
	<?php
	if(THIS_PAGE!="recettes" && THIS_PAGE!="account" && THIS_PAGE!="cart" && THIS_PAGE!="checkout") require_once("right_part.php");
	?>
	<td width="10" background="images/ombre_droite.png"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
</tr>
</table>
<table width="1100" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
	<td background="images/ombre_gauche.png"><img src="images/blank.png" width="10" height="1" border="0" alt=""></td>
	<td valign="top" align="center" style="background-image: url('images/fond_milieu.png'); color:white; border: 1px solid white;width:100%;<?php if (!isset($_GET["impr"])){ ?><?php } ?>">
	<br>&copy; Château Val Joanis 2006 - Tous droits réservés<br>
	Le Club joanis - Château Val Joanis - 2404 route de villelaure 84120 Pertuis
	<br>Tél : 04 90 79 20 77 - eMail : <a href="mailto:info@clubjoanis.com">info@clubjoanis.com</a><br><br>
	</td>
	<td background="images/ombre_droite.png"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
</tr>
<tr>
	<td background="images/ombre_bas_gauche.png"><img src="images/blank.gif" width="10" height="10" border="0" alt=""></td>
	<td background="images/ombre_bas.png"><img src="images/blank.gif" width="1" height="10" border="0" alt=""></td>
	<td background="images/ombre_bas_droite.png"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
</tr>
</table>
<div align="center" class="small">
	<a href="http://www.vinternet.net" target="_blank">VINTERNET</a> - 
	<a href="#" onClick="window.open('mentions.php','_blank','toolbar=0, location=0, directories=0, status=0, scrollbars=0, resizable=0, copyhistory=0, menuBar=0, width=600, height=295,left=0,top=0');return(false)">Mentions légales</a> - 
	L'abus d'alcool est dangereux pour la santé<br/><br/>
</div>
<div align="center" class="small">
	<img style="padding-top:5px;" src="images/CA.jpg"/>
</div>
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
