<?php
session_start();
$auth = $_SESSION["auth"];

if(!$auth["user_id"]){
	header("Location: http://" . $_SERVER['HTTP_HOST']
     . dirname($_SERVER['PHP_SELF'])
     . "/login.php?PHPSESSID=".session_id()."&redir=account.php");
	exit;
}

include_once("header.php");

// Librairies
require("inc/ps_login.php");
$ps_login = new ps_login;
require_once("inc/ps_orders.php");
$ps_order = new ps_orders;

if (isset($vars["profile"]) && $vars["user_id"]==$auth["user_id"]) {
	if(!isset($vars["ship_id"])) $vars["ship_id"] = 0;
	$ok 			= $ps_order->modify_user($vars);
}

if($auth["user_id"] && !$admin){
	$user_id = $auth["user_id"];
}else{
  $user_id = $vars["user_id"];
}

$user = $ps_login->userInfos($user_id);
if(isset($_SESSION["order_id"])){
	$ps_order->modify($user_id, $_SESSION["order_id"], $_SESSION["cart"]);
	$orderInfo = $ps_order->read($_SESSION["order_id"], $user_id);
}
?>

<form name="cart" method="POST" action="<?php echo (isset($vars["profile"])) ? "account" : "checkout"; ?>.php">
<input type='hidden' name='user_id' value='<?php echo $user_id; ?>'>
<input type='hidden' name='profile' value='<?php echo $vars["profile"]; ?>'>

<TABLE width="100%" cellspacing="0" cellpadding="0" border="0" align="left">
<tr valign="top">
<td valign="top" width=50%>

	<table>
	<tr><td colspan=3>
	<span class=titre>Adresse de facturation :</span><br>
	<hr size="1" align="left" color="black" width="95%"><br>
	</td></tr>

	<tr><td></td>
	<td></td>
	<td>
	<select name="bill_gender">
		<option value="Mme" <?php if($user["bill_gender"]=="Mme") echo "selected" ?>>Mme
		<option value="Mlle" <?php if($user["bill_gender"]=="Mlle") echo "selected" ?>>Mlle
		<option value="Mr" <?php if($user["bill_gender"]=="Mr" || $user["bill_gender"]=="") echo "selected" ?>>Mr
	</select>
	</td></tr>

	<tr><td>
	Société : </td>
	<td></td>
	<td>
	<input type=text maxlength=64 name='bill_company' value="<?php echo $user["bill_company"]; ?>" size=25 class=form>
	</td></tr>

	<tr><td>
	Nom : </td>
	<td></td>
	<td>
	<input type=text maxlength=64 name='bill_name' value="<?php echo $user["bill_name"]; ?>" size=25 class=form>
	</td></tr>

	<tr><td>
	Prénom : </td>
	<td></td>
	<td>
	<input type=text maxlength=64 name='bill_first_name' value="<?php echo $user["bill_first_name"]; ?>" size=25 class=form>
	</td></tr>

	<tr><td VALIGN=top>
	Adresse : </td>
	<td></td>
	<td>
	<input type=text maxlength=64 name=bill_address1 value="<?php echo $user["bill_address1"]; ?>" size=25 class=form><br>
	<input type=text maxlength=64 name=bill_address2 value="<?php echo $user["bill_address2"]; ?>" size=25 class=form>
	</td></tr>
	<tr><td>
	Code Postal : </td>
	<td></td>
	<td>
	<input type=text maxlength=10 name=bill_zip value="<?php echo $user["bill_zip"]; ?>" size=10 class=form>
	</td></tr>
	<tr><td>
	Ville : </td>
	<td></td>
	<td>
	<input type=text maxlength=64 name=bill_city value="<?php echo $user["bill_city"]; ?>" size=25 class=form>
	</td></tr>
	<tr><td>
	Email : </td>
	<td></td>
	<td>
	<input type=text maxlength=64 name='user_email' value="<?php echo $user["user_email"]; ?>" size=25 class=form>
	</td></tr>

	<?php
	if (isset($vars["profile"])) {
		?>
		<tr><td valign="top">
		Mot de passe : </td>
		<td></td>
		<td>
		<input type=text class=form maxlength=64 name='password' value='<?php if(!$user["password"]){ echo '0'; }else{ echo $user["password"]; } ?>' size=25>
		<br><font color=red size=1>Vous pouvez modifier ce mot de passe</font></td></tr>
		<?php
	}
	?>

	<tr><td>
	Téléphones : </td>
	<td></td>
	<td>&nbsp;
	</td></tr>

	<tr><td>
	Domicile : </td>
	<td></td>
	<td>
	<input type=text maxlength=23 name=bill_phone1 value="<?php echo $user["bill_phone1"]; ?>" size=12 class=form>
	</td></tr>
	<tr><td>
	Bureau : </td>
	<td></td>
	<td>
	<input type=text maxlength=23 name=bill_phone2 value="<?php echo $user["bill_phone2"]; ?>" size=12 class=form>
	</td></tr>
	<tr><td>
	<strong>Mobile :</strong> </td>
	<td></td>
	<td>
	<input type=text maxlength=23 name=bill_mobile value="<?php echo $user["bill_mobile"]; ?>" size=12 class=form>
	</td></tr>

	</table>

</td>

<td>&nbsp;</td>

<td align=left width=50%>

	<script language=Javascript>
	var nava 			= (document.layers);
	var dom 			= (document.getElementById);
	var iex 			= (document.all);

	function sh(itemID, act, dis){
		if (nava) 		{ var elem = document.itemID }
		else if (dom) { var elem = document.getElementById(itemID) }
		else if (iex) { var elem = document.all(itemID) }

		elem.style.visibility = act;
		elem.style.display = dis;
	}
	</script>

	<table>
	<tr><td colspan=3>
	<span class=titre>Adresse de livraison :</span><br>
	<hr size="1" align="left" color="black" width="95%">

	<span class=small>Vous pouvez gérer différentes adresses de livraison associées à votre adresse de facturation.
	Cela vous permettra de vous faire livrer à différentes adresses ou d'utiliser le club Joanis pour offrir du vin.
	Par défaut votre adresse de livraison sera identique à celle de facturation.</span>
	</td></tr>

	<?php if (!isset($vars["profile"])) { ?>
		<tr><td colspan=3>
		<input type="radio" name="ship_id" value="0" checked="1" class="normal"
			onClick="hideAll()">
		Identique à l'adresse de facturation
		</td></tr>
	<?php } ?>
	</table>

	<?php
	// adresses de livraison
	$req = "select * from user_ship where user_id=$user_id order by ship_id desc";
	$db->query($req);
	$i = 0;
	while($user = $db->next_array()){
	  $ship_id 		= $user["ship_id"];
	  $ship_label = $user["ship_first_name"] . " " . $user["ship_name"] . " " . $user["ship_address1"];

	  if($i==0) $max_ship_id = $ship_id;
	  if($ship_id==0){
			$ship_id = $max_ship_id+1;
			$ship_label = "Créer une nouvelle adresse de livraison";
			$ship_add = "1";
			unset($user);
		}
		$i++;
		?>

		<table>
		<tr><td>
		<input type="radio" name="ship_id" value="<?php echo $ship_id ?>" class="normal"
			onClick="hideAll(); sh('ship_addr<?php echo $ship_id ?>', 'visible', 'block');">
		<input type="hidden" name ="<?php echo "$i#"; ?>add_ship" value="<?php echo $ship_add ?>">
		<?php echo $ship_label ?>
		</td></tr>
		</table>

		<div id="ship_addr<?php echo $ship_id ?>" style="position: relative;	visibility: hidden;	z-index: 11; display: none;">
		<table>
		<tr><td></td>
		<td></td>
		<td>
		<select name="<?php echo "$ship_id#"; ?>ship_gender">
		  <option value="Mr" <?php if($user["ship_gender"]=="Mr" || $user["ship_gender"]=="") echo "selected" ?>>Mr
			<option value="Mme" <?php if($user["ship_gender"]=="Mme") echo "selected" ?>>Mme
			<option value="Mlle" <?php if($user["ship_gender"]=="Mlle") echo "selected" ?>>Mlle
		</select>
		</td></tr>

		<tr><td>
		Société : </td>
		<td></td>
		<td>
		<input type=text maxlength=64 name='<?php echo "$ship_id#"; ?>ship_company' value="<?php echo $user["ship_company"]; ?>" size=25 class=form>
		</td></tr>

		<tr><td>
		Nom : </td>
		<td></td>
		<td>
		<input type=text maxlength=64 name='<?php echo "$ship_id#"; ?>ship_name' value="<?php echo $user["ship_name"]; ?>" size=25 class=form>
		</td></tr>

		<tr><td>
		Prénom : </td>
		<td></td>
		<td>
		<input type=text maxlength=64 name='<?php echo "$ship_id#"; ?>ship_first_name' value="<?php echo $user["ship_first_name"]; ?>" size=25 class=form>
		</td></tr>

		<tr>
		<td VALIGN=top>
		Adresse : </td>
		<td></td>
		<td>
		<input type=text maxlength=64 name=<?php echo "$ship_id#"; ?>ship_address1 value="<?php echo $user["ship_address1"]; ?>" size=25 class=form><br>
		<input type=text maxlength=64 name=<?php echo "$ship_id#"; ?>ship_address2 value="<?php echo $user["ship_address2"]; ?>" size=25 class=form>
		</td></tr>

		<tr><td>
		Code Postal : </td>
		<td></td>
		<td>
		<input type=text maxlength=10 name=<?php echo "$ship_id#"; ?>ship_zip value='<?php echo $user["ship_zip"]; ?>' size=10 class=form>
		</td></tr>

		<tr><td>
		Ville : </td>
		<td></td>
		<td>
		<input type=text maxlength=64 name=<?php echo "$ship_id#"; ?>ship_city value="<?php echo $user["ship_city"]; ?>" size=25 class=form>
		</td></tr>

		<tr><td>
		Téléphones : </td>
		<td></td>
		<td>&nbsp;
		</td></tr>

		<tr><td>
		Domicile : </td>
		<td></td>
		<td>
		<input type=text maxlength=23 name=<?php echo "$ship_id#"; ?>ship_phone1 value='<?php echo $user["ship_phone1"]; ?>' size=12 class=form>
		</td></tr>

		<tr><td>
		Bureau : </td>
		<td></td>
		<td>
		<input type=text maxlength=23 name=<?php echo "$ship_id#"; ?>ship_phone2 value='<?php echo $user["ship_phone2"]; ?>' size=12 class=form>
		</td></tr>

		<tr><td>
		<strong>Mobile :</strong> </td>
		<td></td>
		<td>
		<input type=text maxlength=23 name=<?php echo "$ship_id#"; ?>ship_mobile value='<?php echo $user["ship_mobile"]; ?>' size=12 class=form>
		</td></tr>

		</table>
		</div>

		<?php
		unset($user);
	} // end while (adresses de livraison)
	?>
</td>
</tr>
</table>

<br clear="all">

<?php if (isset($vars["profile"])) { ?>

	<?php if($auth["discount"]>0 && $auth["discount"]<25) {
		print "<br>Vous disposez d'une remise personnelle de".number_format($auth["discount"], 0)."% ".$auth["discount_txt"];
	} ?>

	<div align="center">
	<br><br>

	<a href="#"
		onClick="document.forms['cart'].submit();">
	<img src="images/modifier.gif" name="Mail.x" value=" Enregistrer mes modifications " border=0 class="normal"></a>
	</FORM>
	</div>

<?php }else{ ?>

	<br>Instructions complémentaires concernant la commande (code, étage, etc) : <br><br>
	<textarea cols="70" rows="3" name="order_div1"><?php echo $orderInfo["order_div1"] ?></textarea>

	<br><br>Message a joindre avec le colis : <br><br>
	<textarea cols="70" rows="3" name="order_div2"><?php echo $orderInfo["order_div2"] ?></textarea>
	
	<br><br><input type="checkbox" name="valid_cgv" value="1">&nbsp;Je déclare être &acirc;gé de plus de 16 ans,  avoir pris connaissances des <a href="cgdv.php">conditions g&eacute;n&eacute;rales de vente</a> et les accepter.

  <br><br>
	Mode de règlement :
	<input type="radio" id="mode_paiement1" name="mode_paiement" value="CB" checked style="border:0px; background-color:white"><label for="mode_paiement1">CB sécurisée</label>
	<input type="radio" id="mode_paiement2" name="mode_paiement" value="CHQ" style="border:0px; background-color:white"><label for="mode_paiement2">Chèque</label>

	<div align="center">

	<span class="small"><br><br>
	Merci de v&eacute;rifier et de compl&eacute;ter vos coordonn&eacute;es,
	puis de valider votre commande :</span>
	<br><br>

	<a href="#"
		onClick="if(document.forms['cart'].valid_cgv.checked==false){
	  alert('Merci de confirmer votre acceptation des conditions générales de vente.');
	}else{
		if(document.forms['cart'].bill_phone1.value=='' && document.forms['cart'].bill_mobile.value==''){
		  alert('Veuillez remplir le champ Téléphone afin de faciliter votre livraison.');
		}else{
			if(confirm('Votre commande va être enregistrée.\n Cliquez sur &laquo; Annuler &raquo; si vous souhaitez encore y apporter des modifications\nou sur &laquo; OK &raquo; pour continuer.')){
				document.forms['cart'].submit();
			}else{
				return;
			}
		}
	}">
	<img src="images/enregistrer.gif" name="Mail.x" value=" Enregistrer ma commande " border=0 class="normal"></a>
	</FORM>
	</div>

<?php } ?>

<script language=Javascript>

function hideAll(){
	i=1;
	while(i<=<?php echo $ship_id ?>){
		sh('ship_addr'+i, 'hidden', 'none');
		i++;
	}
}

</script>

<?php
include_once("footer.php");
?>
