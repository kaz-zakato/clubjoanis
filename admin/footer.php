</td>
	<td width="10" background="../images/ombre_droite.png"><img src="../images/blank.gif" width="10" height="1" border="0" alt=""></td>

</tr>
<tr >
	
		<td background="../images/ombre_bas_gauche.png">
			<img border="0" width="10" height="10" alt="" src="../images/blank.gif">
		</td>
		<td colspan="2" background="../images/ombre_bas.png">
			<img border="0" width="1" height="10" alt="" src="../images/blank.gif">
		</td>
		<td background="../images/ombre_bas_droite.png">
			<img border="0" width="10" height="1" alt="" src="../images/blank.gif">
		</td>
</tr>
</table>

<!-- DEBUG -->
<br clear=all>
<pre>
<?php
if(isset($_GET["debug"]) && $_GET["debug"]=="1"){
	print_r($_SESSION);
}else{
	print "<!--";
	print_r($_SESSION);
	print "-->";
}
?>
</pre>
<!-- DEBUG -->

</BODY>
</HTML>
