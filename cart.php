<?php
include_once("header.php");

$cart = $_SESSION["cart"];
if (sizeof($cart) == 0) {
	?>
	<br><span class="titre">Votre panier</span><br>
		<hr size="1" align="left" color="marroon" width="95%"><br>
		<font color="#7c3c3e">Vous devez commander 12 bouteilles au minimum </font><font color="marroon"><br /></font> Livraison en France Métropolitaine uniquement</font><br />
	<?php
	echo "<br><br><p align=center>".$empty_cart."</p>";
	return;
}

if (THIS_PAGE!="checkout") {
	?>
	<br><span class="titre">Votre panier</span><br>
	<b><font color="#7c3c3e">Vous devez commander 12 bouteilles au minimum </font><font color="#7c3c3e"><br /> Livraison en France Métropolitaine uniquement</font></b><br />
	<hr size="1" align="left" color="#7c3c3e" width="100%"><br>
	<?php
}
?>

<TABLE width="100%" CELLSPACING="0" CELLPADDING="0" BORDER="0" align="center">
<tr class="cart">
	<td>
	<TABLE CELLSPACING="0" width="100%" CELLPADDING="0" BORDER="0">
	<tr>
		<td ALIGN="left" class="small"><b><?php echo CART_NAME; ?></b></td>
		<td ALIGN="center" width="10%" class="small"><b><?php echo CART_PRICE; ?></b></td>
		<td ALIGN="center" width="15%" class="small"><b><?php echo CART_QUANTITY; ?></b></td>
		<td ALIGN="right" width="20%" class="small"><b><?php echo CART_SUBTOTAL; ?></b></td>
		<td ALIGN="right" width="20">&nbsp;</td>
	</tr>

	<?php
	$total	   = 0;
	$total_qty = 0;

	for ($i=0;$i<sizeof($cart);$i++) {
		if ($i % 2) $row_color = SEARCH_COLOR_2;
		else $row_color = SEARCH_COLOR_1;
		
		if(THIS_PAGE!="checkout"){
			?>
			<FORM ACTION="<?php echo THIS_PAGE ?>.php" METHOD="get" name="form<?php echo $cart[$i]["product_id"] ?>">
			<INPUT TYPE=HIDDEN NAME="ship_to_info_id" VALUE=<?php echo $ship_to_info_id ?> >
			<INPUT TYPE=HIDDEN NAME="page" VALUE="cart">
			<INPUT TYPE=HIDDEN NAME="func" VALUE="cartUpdate">
			<INPUT TYPE=HIDDEN NAME="product_id" VALUE=<?php echo $cart[$i]["product_id"] ?>>
			<?php
		}
		?>
		
		<TR>
			<TD colspan=5 ALIGN=RIGHT><hr size=1 align=right width=100%></TD>
	 	</TR>
		
		<TR VALIGN="top">
		<TD>
		<b><a href="product.php?product=<?php echo $cart[$i]["product_id"] ?>">
		<?php echo stripslashes($cart[$i]["label"]); ?></a></b><br><?php echo stripslashes($cart[$i]["company"]); ?>
		</TD>

		<TD ALIGN="center">
		<?php
		if(!$vars["order_id"]){
			$price = $ps_product->get_price($cart[$i]["product_id"]);
		}else{
			$price = $ps_product->get_order_price($cart[$i]["product_id"],$order_id);
		}
		print number_format($price["product_price"], 2, ',', ' ');
		?> €
		</TD>
		
		<TD ALIGN="center">
		<?php if(THIS_PAGE!="checkout"){
			echo $ps_product->select_list($cart[$i]["product_id"],$i,$price["product_min_qty"],$price["product_max_qty"]); ?>
			<br>
			<?php echo "<a href='cart.php?product_id=" . $cart[$i]["product_id"] . "&func=cartDelete' style='font-size:10px;color:white;text-decoration:none;'>"; ?>
			Supprimer</a>
			<?php
		}else{
			echo $cart[$i]["quantity"];
		}
		$total_qty += $cart[$i]["quantity"];
		?>
		</TD>

		<?php
		if(THIS_PAGE!="checkout"){
			?>
			</FORM>
			<?php
		}
		?>
		
		<TD ALIGN="right">
		<?php
		$subtotal = $price["product_price"] * $cart[$i]["quantity"];
		$total += $subtotal;
		print number_format($subtotal, 2, ',', ' ');
		?> €
		</TD>
		<td ALIGN="right" width="20">&nbsp;</td>
		</TR>
		
		<?php
	} // End for loop
	?>
	

	<TR >
		<TD colspan=5 ALIGN=RIGHT><hr size=1 align=right width=100%></TD>
 	</TR>
	
	<TR>
		<TD COLSPAN=3 ALIGN=RIGHT style="line-height:20px;"><b>Sous-total :</b></TD>
		<TD ALIGN=right><b><?php print number_format($total, 2, ',', ' ') ?> €</b></TD>
		<td ALIGN="right" width="20">&nbsp;</td>
	</TR>
	<!--
	<TR bgcolor=white>
		<TD COLSPAN=3 ALIGN=RIGHT style="line-height:20px;">Remise quantitative (*) : </TD>
		<TD align=right>
		<?php
		$order_shipping = $ps_cart->get_shipping($total);
		/*$order_discount = $ps_cart->get_discount($total_qty);
		$order_discount = $total*($order_discount/100);
		print number_format($order_discount, 2, ',', ' ');*/
		?> €</TD>
	</TR>
	-->
	<?php /*if($auth["user_id"] && $auth["discount"]>0 && $auth["discount"]<25){ ?>
	<TR bgcolor=white>
		<TD COLSPAN=3 ALIGN=RIGHT style="line-height:20px;">Remise personnelle (**) : </TD>
		<TD align=right>
		<?php
		$user_discount = $auth["discount"];
		$order_total = $total - $order_discount;
		$user_discount = $total*($user_discount/100);
		print number_format($user_discount, 2, ',', ' ');
		?> €</TD>
		<td ALIGN="right" width="20">&nbsp;</td>
	</TR>
	<?php }
*/
	//$order_total 	= $order_shipping + $total - $order_discount - $user_discount;
	$order_total 	= $total;
	$tva 			= $order_total*0.196;
	 ?>
	
	<TR>
		<TD COLSPAN=3 ALIGN=RIGHT style="line-height:20px;">Dont TVA : </TD>
		<TD align=right><?php print number_format($tva, 2, ',', ' '); ?> €</TD>
		<td ALIGN="right" width="20">&nbsp;</td>
	</TR>
	
	<TR>
		<TD COLSPAN=3 ALIGN=RIGHT>Frais de port : </TD>
		<TD align=right>
		<?php
		if($order_total<260){
			$order_total += $order_shipping;
			print number_format($order_shipping, 2, ',', ' ');
		}
		else print number_format(0, 2, ',', ' ');
		?> €</TD>
		<td ALIGN="right" width="20">&nbsp;</td>
	</TR>
	
	<TR>
		<TD colspan=5 ALIGN=RIGHT><hr size=1 align=right width=35%>
		</TD>
 	</TR>
 
	<TR>
		<TD COLSPAN=3 ALIGN=RIGHT><b>Total TTC :</b> </TD>
		<TD align=right><b>
		<?php
		//$order_total += $order_shipping;
		print number_format($order_total, 2, ',', ' ');
		?> €
		</b></TD>
		<td ALIGN="right" width="20">&nbsp;</td>
	</TR>
	</TABLE>
</TD></TR>
</TABLE>



<br><br>

<?php
if (THIS_PAGE=="cart") {
  $txt 			= "";
  $destPage = "account";
  ?>
	<div align="center" class="small">
		<FORM ACTION="account.php" METHOD="get" NAME="cart">
			<input type="hidden" name="page" value="<?php echo $destPage ?>">
			<?php if($total_qty < 12){ ?>
			<img src="images/terminer.png" name="Mail.x" value=" Enregistrer ma commande " border=0 onclick="alert('Vous devez effectuer une commande de 12 bouteilles minimum');">
			<?php } else { ?>
			<a href="account.php"><img src="images/terminer.png" name="Mail.x" value=" Enregistrer ma commande " border=0></a>
			<?php } ?>
		</FORM>
		<!--<br>(*) 3% de réduction à partir de 36 bouteilles-->
		<?php if($auth["user_id"] && $auth["discount"]>0 && $auth["discount"]<25){ ?>
			<br>(**) <?php echo $auth["first_name"] . " " . $auth["last_name"] ?>, vous disposez d'une remise personnelle de <?php echo number_format($auth["discount"], 0) ?> % <?php echo $auth["discount_txt"] ?>
		<?php } ?>
		<br><br>
	</div>
	<?php
	include_once("footer.php");
}
?>
