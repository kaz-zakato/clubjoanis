<?php
include_once("header.php");
?>
<br><span class="titre">ANNULATION DE COMMANDE</span><br>
<hr size="1" align="left" color="marroon" width="95%"><br>
<?php
if(!$_GET["auto"] && !$_GET["erreur"]){
	require("inc/ps_orders.php");
	$ps_orders 	= new ps_orders;
	
	$tabTransaction	= explode("A", $_GET["ref"]);
	$idTransaction	= $tabTransaction[0];
	$tabSession		= explode("_", $tabTransaction[1]);
	$user_id		= $tabSession[0];
	$session_id		= $tabSession[1];
	$orderid 		= $ps_orders->get_order_id($idTransaction);
	
	require("inc/ps_login.php");
	$ps_login = new ps_login;
	$user = $ps_login->userInfos($user_id); ?>
	Votre commande n'a pas été validée.
	<br>
	Votre compte bancaire ne sera pas débité.
	<?
	$orderInfo 	= $ps_orders->validate($user_id, $orderid, $user["user_email"], 1, "CB");
	//mail("mouchard@vinternet.net", "Le Club Joanis : annulation", "Code erreur = annulation de commande","From: ".FROM."\nReply-To: ".FROM."\nReturn-Path: <".FROM.">\nContent-Type: text/text; charset=utf-8\"UTF-8\"\nContent-Transfer-Encoding: 8bit");
}
include_once("footer.php");
?>
