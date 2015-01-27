<?php
require("../global_vars.php4");

//  analyse du code retour
if($_GET["auto"] && $_GET["erreur"]=="00000"){
	require("../ps_orders.php4");
	$ps_orders 	= new ps_orders;
	
	$tabTransaction	= explode("A", $_GET["ref"]);
	$idTransaction	= $tabTransaction[0];
	$user_id		= $tabTransaction[1];
	//$tabSession		= explode("_", $tabTransaction[1]);
	//$user_id		= $tabSession[0];
	//$session_id		= $tabSession[1];
	$orderid 		= $ps_orders->get_order_id($idTransaction);
	
	// Fichiers de configuration
	session_start();
	session_unset();
	session_destroy();
	require("../common_vars.php4");

	error_reporting(E_ALL ^ E_NOTICE);

	// Librairies
	require("../db_mysql.php4");
	$db 				= new DB;
	require("../ps_login.php4");
	$ps_login 	= new ps_login;
	$user_infos = $ps_login->userInfos($user_id);
	/*$ps_login->checkuser($user_infos["user_email"], $user_infos["password"],
		"../../confirm.php4?order_id=$orderid&user_id=$user_id&PHPSESSID=$session_id");*/
		//echo "../../confirm.php4?order_id=$orderid&user_id=$user_id&PHPSESSID=".session_id();
	$ps_login->checkuser($user_infos["user_email"], $user_infos["password"],
		"../../confirm.php4?order_id=$orderid&user_id=$user_id&PHPSESSID=".session_id());
	exit;
}
else if($_GET["NUMERR"]){
  	print("Une erreur s'est produite lors de votre tentative de paiement.<br>\n");
  	if($_GET["NUMERR"]=="-16"){
		print("<strong>Votre adresse email est incorrecte</strong>.<br>\n");
		print("Merci de bien vouloir <a href=\"javascript:history.back();\">revenir</a> sur notre formulaire pour la ressaisir...<br><br>\n");
	}
	else{
		print("Vous pouvez nous envoyer un email au sujet de cette erreur en <a href=\"mailto:".FROM."?subject=Code erreur ".$_GET["NUMERR"]."\">cliquant ici</a>.<br><br>\n");
	}
	print("Votre commande n'a pas été validée.\n<br>\nVotre compte bancaire ne sera pas débité.<br><br>\n");
	print("Vous pouvez revenir à la boutique en <a href=\"http://www.clubchancel.com\">cliquant ici</a><br><br>\n");
}
else{
  	print("Une erreur s'est produite lors de votre tentative de paiement.<br>\n");
  	print("Votre commande n'a pas été validée.\n<br>\nVotre compte bancaire ne sera pas débité.<br><br>\n");
	print("Vous pouvez revenir à la boutique en <a href=\"http://www.clubchancel.com\">cliquant ici</a><br><br>\n");
  	mail("mouchard@vinternet.net", "Le Club Chancel : erreur appel response", "Erreur = ".$_GET["erreur"],"From: ".FROM."\nReply-To: ".FROM."\nReturn-Path: <".FROM.">\n charset=iso-8859-1\"iso-8859-1\"\nContent-Transfer-Encoding: 8bit");
}
?>
