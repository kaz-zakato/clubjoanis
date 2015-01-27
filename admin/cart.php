<?php
if (sizeof($cart) == 0) {
	echo $empty_cart;
	$checkout = False;
}else {
	$checkout = True;
	if ($page!="checkout") {
		?>
		<p></p>
		<font size=+1>Votre Panier</font>
		<hr align="left" width="100%" size="1" color="Maroon" noshade>
		<br>
		<?php
	}
	$mail_message = "";
	?>
	<TABLE align="center" width=100% CELLSPACING=0 CELLPADDING=0 BORDER=0>
	<tr bgcolor=black><td>
	<TABLE CELLSPACING=1 width=100% CELLPADDING=2 BORDER=0>
		<TR BGCOLOR=<?php echo $cart_header_color?>>
			<Td ALIGN=left><?php echo $cart_name; ?></td>
			<Td ALIGN=middle width=10%><?php echo $cart_price; ?></Td>
			<Td ALIGN=middle width=15%><?php echo $cart_quantity; ?></Td>
			<Td ALIGN=middle width=20%><?php echo $cart_subtotal; ?></Td>
		</TR>
		
		<?php
		$total = 0;
		$total_qty = 0;
		$champagne = 0;
		
		for ($i=0;$i<sizeof($cart);$i++) {
			if ($i % 2) $row_color = SEARCH_COLOR_2;
			else $row_color = SEARCH_COLOR_1;
			if(strtolower($cart[$i]["region"])=="champagne") $champagne += $cart[$i]["quantity"];
			?>
			<FORM ACTION="<?php echo URL ?>" METHOD=get name="form<?php echo $cart[$i]["product_id"]?>">
			<INPUT TYPE=HIDDEN NAME=ship_to_info_id VALUE=<?php echo $ship_to_info_id?> >
			<INPUT TYPE=HIDDEN NAME=page VALUE="cart">
			<INPUT TYPE=HIDDEN NAME=func VALUE="cartUpdate">
			<INPUT TYPE=HIDDEN NAME=product_id VALUE=<?php echo $cart[$i]["product_id"]?>>
			
			<TR VALIGN=TOP BGCOLOR=<?php echo $row_color ?>>
			<TD>
			<b><?php echo $cart[$i]["label"]; ?></b><br><?php echo $cart[$i]["company"]; ?>
			</TD>

			<TD ALIGN=middle>
			<?php 
			if(!$order_id){
				$price = $ps_product->get_price($cart[$i]["product_id"]);
			}else{
				$price = $ps_product->get_order_price($cart[$i]["product_id"],$order_id);
			}
			printf("%.2f", $price["product_price"]);
			?>
			</TD>
			<TD ALIGN=middle>
			<?php if(THIS_PAGE=="cart"){
				echo $ps_product->select_list($cart[$i]["product_id"],$i,$price["product_min_qty"]); ?>
				<!--- <input type="image" src="bouton_ok.gif" width="26" height="17" alt="" border="0"> ---><br>
				<?php echo "<a href='cart.php?product_id=" . $cart[$i]["product_id"] . "&func=cartDelete'>"; ?><font size="-2">Supprimer</font></a>
				<?php
			}else{
				echo $cart[$i]["quantity"];
			}
			$total_qty += $cart[$i]["quantity"];
			?>
			</TD></FORM>
			<TD ALIGN=middle>
			<?php
			$subtotal = $price["product_price"] * $cart[$i]["quantity"];
			$total += $subtotal;
			printf("%.2f", $subtotal);
			?> €
			</TD>
			</TR>
			<?php
			$mail_message .= $cart[$i]["quantity"] . " x\t" . $cart[$i]["label"] . " - " . $cart[$i]["company"] . "  - Prix : " . sprintf("%.2f", $price["product_price"]) . " euros TTC /btle\r";
			//$weight_subtotal = $ps_intershipper->get_weight($cart[$i]["product_id"]);
			//$weight_total += $weight_subtotal;
		} // End for loop
		?>
		<TR bgcolor=white>
			<TD COLSPAN=3 ALIGN=RIGHT></TD> 
			<TD ALIGN=middle></b></TD>
		</TR>
		<TR bgcolor=white>
			<TD COLSPAN=3 ALIGN=RIGHT><b>Sous-total:</b></TD> 
			<TD ALIGN=middle><b><?php printf("%.2f", $total); ?> €</b></TD>
		</TR>
		<TR bgcolor=white>
			<TD COLSPAN=3 ALIGN=RIGHT>Dont TVA : </TD> 
			<TD align=middle><?php printf("%.2f", $total/19,6); ?> €</TD>
		</TR>
		<TR bgcolor=white>
			<TD COLSPAN=3 ALIGN=RIGHT>Participation aux frais de port : </TD> 
			<TD align=middle>
			<?php
			if($total_qty>35){
				$order_shipping = 0;
			}else if($champagne>17){
				$order_shipping = 0;
			}else{
				$order_shipping = 15;
			}
			
			$mail_message .= "\n\nFrais de port : " . sprintf("%.2f", $order_shipping) . " euros TTC";
			printf("%.2f", $order_shipping);
			?> €</TD>
		</TR>
		<TR bgcolor=white>
			<TD COLSPAN=3 ALIGN=RIGHT><b>Total:</b> </TD>
			<TD align=middle>
			<?php
			$order_total = $order_shipping + $total;
			printf("<B>%.2f</B>", $order_total);
			?> €</TD>
		</TR>
	</TABLE>
	</td></tr>
	</table>

	<br><br>
	<?php
	if (THIS_PAGE=="cart") {
		?>
		<p align="center"><FORM ACTION="" METHOD=get>
		<input type="hidden" name="page" value="account">
		<input type="submit" name="Mail.x" value="  Terminer la commande " class="Bsbttn">
		</FORM></p>
		<?php
	}else if (THIS_PAGE=="account") {
		?>
		<p align="center"><FORM ACTION="" METHOD=get>
		<input type="hidden" name="page" value="checkout">
		<input type="button" name="Mail.x" value="  Enregistrer et imprimer ma commande "  onClick="if(confirm('Votre commande va être enregistrée.\n Cliquez sur &laquo; Annuler &raquo; si vous souhaitez encore y apporter des modifications\n ou sur &laquo; OK &raquo; pour continuer.')){ document.cart.submit(); }else{ return; }" class="Bsbttn">
		</FORM></p>
		<?php
	}else{
		echo "";
	}
}
?>

<script language="JavaScript">
<!--- 
function confirmation(){
	yesNo = confirm('Votre commande va être enregistrée.\n Cliquez sur \"Annuler\" si vous souhaitez encore y apporter des modifications\n ou sur \"OK\" pour continuer.');
	if(yesNo) document.cart.submit();
	else return;
}
 --->
</script>
