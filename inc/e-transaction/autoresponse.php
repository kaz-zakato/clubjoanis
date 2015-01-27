<?php
require("../global_vars.php4");
error_reporting(0);
//  analyse du code retour
// OK, Sauvegarde des champs de la réponse

if(isset($_REQUEST["auto"]) && isset($_REQUEST["erreur"])){
	// Fichiers de configuration
	require("../common_vars.php4");

	//error_reporting(E_ALL ^ E_NOTICE);

	// Librairies
	require("../db_mysql.php4");
	$db			= new DB;
	require("../ps_cart.php4");
	$ps_cart	= new ps_cart;
	require("../ps_orders.php4");
	$ps_orders 	= new ps_orders;
	require("../ps_login.php4");
	$ps_login 	= new ps_login;
	
	$tabTransaction	= explode("A", $_REQUEST["ref"]);
	$idTransaction	= $tabTransaction[0];
	$user_id		= $tabTransaction[1];
	//$tabSession		= explode("_", $tabTransaction[1]);
	//$user_id		= $tabSession[0];
	//$session_id		= $tabSession[1];
	$order_id		= $ps_orders->get_order_id($idTransaction);
	$user_infos		= $ps_login->userInfos($user_id);

	if($_REQUEST["erreur"]=="00000"){
		//echo $erreur;
		$orderInfo 	= $ps_orders->validate($user_id, $order_id, $user_infos["user_email"], 2, "CB");
	}
	else{
		//echo $erreur;
		$orderInfo 	= $ps_orders->validate($user_id, $order_id, $user_infos["user_email"], 1, "CB");
		mail("mouchard@vinternet.net", "Le Club Chancel : erreur appel response", "Code erreur = ".$_REQUEST["erreur"],"From: ".FROM."\nReply-To: ".FROM."\nReturn-Path: <".FROM.">\n charset=iso-8859-1\"iso-8859-1\"\nContent-Transfer-Encoding: 8bit");
	}
	exit;
}
?>
