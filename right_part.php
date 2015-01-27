<?php if(preg_match("/home/", $CurrentPage)){ ?>
<td width="252" valign="top" style="border-top:1px solid white;border-right:1px solid white;background-color: #342925;">
  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr>
	  	<td class="white" style="padding: 9px 9px 9px 9px;">
		<?php
		$queryEdito="SELECT content_home FROM contents WHERE content_id='1'";
		
		$db->query($queryEdito);
		$db->next_record();
		
		$edito=$db->f("content_home");
		echo nl2br($edito)."<br><br>";
		?>
		</td>
	  </tr>
  </table>
</td>
<td width="1" style="background-color: #FFFFFF;"><img src="images/blank.gif" width="1" height="10" border="0" alt=""></td>
<?php } ?>