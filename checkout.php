<?php
include_once("header.php");
require_once("inc/ps_orders.php");
$ps_order = new ps_orders;
$poursuivre = $_GET[poursuivre];
/*******************************************************************************
* gestion de la commande (lecture / ecriture)
*******************************************************************************/
if(isset($vars["order_id"])) {
  $order_id = $vars["order_id"];
  $user_id  = $vars["user_id"];

}else if(!isset($_SESSION["order_id"])){
	$ok 			= $ps_order->modify_user($vars);
	if($ok)		$order_id = $ps_order->add($auth["user_id"], $_SESSION["cart"], 0, $vars["mode_paiement"]);
	$user_id  = $_SESSION["auth"]["user_id"];
	
}else{
	$order_id = $_SESSION["order_id"];
	$user_id  = $_SESSION["auth"]["user_id"];
	if(isset($vars["user_id"])){
		$ps_order->modify_user($vars);
		$ps_order->modify($auth["user_id"], $order_id, $_SESSION["cart"]);
	}
}

if($vars["mode_paiement"]=="CHQ"){
	$orderInfo 	= $ps_order->validate($user_id, $order_id, $auth["email"], 0, "CHQ");
}else{
  $orderInfo 	= $ps_order->read($order_id, $user_id);
}

/*******************************************************************************
* Affichage du récap de commande
*******************************************************************************/
?>

<br><span class="titre">Commande N°<?php echo $idCommande = $ps_order->get_order_val($orderInfo["order_id"])."A".$user_id; /*."_".session_id();*/ ?> du <?php echo $orderInfo["d5"] ?></span><br><br>
<?php
include("cart.php");

print

"<div style='position:relative; left:0; top:0; height:120px'>
<div style='position: absolute; left: 0; top:0; width:50%;'>
<div class='titre'>Adresse de facturation :</div><hr size='1' align='left' width='90%'>

<br />" . $orderInfo["order_bill_gender"] . " " . $orderInfo["order_bill_name"] . " " . $orderInfo["order_bill_first_name"] . "
<br />" . $orderInfo["order_bill_company"] . "
<br />" . $orderInfo["order_bill_address1"] . " " . $orderInfo["order_bill_address2"] . "
<br />" . $orderInfo["order_bill_zip"] . " " . $orderInfo["order_bill_city"] . "
<br />Tél : " . $orderInfo["order_bill_phone1"] . "<br>Mobile : " . $orderInfo["order_bill_mobile"] . "
<br />Email : " . $orderInfo["user_email"] . "

</div><div style='position: absolute; left: 50%; top:0; width:50%;'>
<div class='titre'>Adresse de Livraison :</div><hr size=1 align=left width=100%>

<br />" . $orderInfo["order_ship_gender"] . " " . $orderInfo["order_ship_name"] . " " . $orderInfo["order_ship_first_name"] . "
<br />" . $orderInfo["order_ship_company"] . "
<br />" . $orderInfo["order_ship_address1"] . " " . $orderInfo["order_ship_address2"] . "
<br />" . $orderInfo["order_ship_zip"] . " " . $orderInfo["order_ship_city"] . "
<br />Tél : " . $orderInfo["order_ship_phone1"] . "<br>Mobile : " . $orderInfo["order_ship_mobile"] . "<br /><br />Livraison sous 10 jours ouvrables.<br>Notez que notre transporteur vous contactera pour prendre rendez-vous avec vous.</div></div>

<br><br><br><br><br><br>
<div class='titre'>Instructions de livraison :</div><hr size='1' align='left' width='100%'>
".$orderInfo["order_div1"]."

<br><br><div class='titre'>Message à joindre avec le colis :</div><hr size='1' align='left' width='100%'>
".$orderInfo["order_div2"]."

<br><br><input type=\"checkbox\" name=\"valid_cgv\" value=1 checked disabled>&nbsp;Je déclare être &acirc;gé de plus de 16 ans,  avoir pris connaissances des conditions g&eacute;n&eacute;rales de vente<br>et les accepter.";

if($vars["mode_paiement"]=="CB" || $poursuivre == 1){
	//include_once("call_request.php");
	//shell_exec("/home/httpd/vhosts/clubjoanis.com/cgi-bin/modulev2.cgi");
	print '<br><br>';
	if($poursuivre != 1)
	{
		print '<div align="center"><a href="?poursuivre=1"><img src="images/poursuivre.gif" border="0"></a></div><br>';
	}
	else
	{
		//echo 'piou';
		include_once("inc/e-transaction/appelTPE.inc.php");
	}	
}else if($vars["mode_paiement"]=="CHQ"){
	print "<p align=center><br>Votre commande est à présent enregistrée.<br>Si vous avez une imprimante, merci de joindre <a href='checkout.php?impr=1' target='cde'>l'impression</a> de cette page à votre réglement. Envoyez l'ensemble à l'adresse suivante :<br></p>
		<p align='center'><strong>Le Club Joanis - Château Val Joanis
		<br>84120 Pertuis</strong><br><br></p>
		";
}else{
	print "<br /><br />";
}
if ($_GET["impr"]=="1") include_once("footer_print.php"); else include_once("footer.php");
?>
