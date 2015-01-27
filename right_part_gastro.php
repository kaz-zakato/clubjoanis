
	<td width="1" background="images/pointillets_v.gif"><img src="images/blank.gif" width="1" height="10" border="0" alt=""></td>
	<td width="1" style="background-color: #FFFFFF;"><img src="images/blank.gif" width="1" height="10" border="0" alt=""></td>
	<td width="252" valign="top" style="background-color: #FF9C31; background-image: url('images/bckg_bottom_right.gif'); background-position: bottom center; background-repeat: no-repeat;">
	  <table border="0" cellpadding="0" cellspacing="0" width="100%">
	  <tr>
	  	<td><img border="0" width="252" src="<?php echo $img_recipe; ?>"></td>
	  </tr>
	  <tr>
	  	<td height="1" style="background-color: #FFFFFF;"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
	  </tr>
	  <tr>
	  	<td class="titres">Idées recettes</td>
	  </tr>
	  <tr>
	  	<td style="padding: 9px 9px 9px 9px; color: white;">
		<ul style="margin: 0px 0px 0px 17px; color: white;">
		<?php
		$list  =
		"SELECT *
			FROM gastronomie
			WHERE enligne='Y'
			ORDER BY date_crea DESC";
		
		$db->query($list);
		while ($db->next_record()){
			$idRecette		= $db->f("recette_id");
			$titres			= stripslashes($db->f("titre"));
			if(strlen($titres)>39) $titres=substr($titres, 0,39)."...";
			if($idRec==$idRecette) echo "<strong>";
			echo "<li><a href=\"?idRec=$idRecette\" style=\"color: white;\">".UCfirst($titres)."</a><br />";
			if($idRec==$idRecette) echo "</strong>";
		}
		?>
		</ul>
		</td>
	  </tr>
	  </table>
	  <br><img src="images/blank.gif" width="1" height="150" border="0" alt="">
	</td>
	<td width="1" style="background-color: #FFFFFF;"><img src="images/blank.gif" width="1" height="10" border="0" alt=""></td>