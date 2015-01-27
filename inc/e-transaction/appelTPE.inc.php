<?php
error_reporting(0);
/*
require_once("inc/ps_orders.php4");
$ps_order = new ps_orders;

echo $user_id  = $_SESSION["auth"]["user_id"];
$order_total;
$orderInfo["order_id"];
$orderInfo["user_email"];
echo $idCommande = $ps_order->get_order_val($orderInfo["order_id"])."A".$user_id;
*/
# passage des valeurs en variables d'environnements

# indiquer au « modulev2.cgi » que les paramètres sont en variables d’environnement
putenv("PBX_MODE=2");
# identifiant de mon site
putenv("PBX_SITE=0575785");
putenv("PBX_RANG=01");
putenv("PBX_IDENTIFIANT=710600878");

# commande
putenv("PBX_TOTAL=".(round($order_total,2)*100));
putenv("PBX_DEVISE=978");
putenv("PBX_CMD=$idCommande");
putenv("PBX_PORTEUR=".$orderInfo["user_email"]);
putenv("PBX_RETOUR=montant:M;ref:R;auto:A;trans:T;idtrans:S;pays:Y;erreur:E;sign:K");

# url de retour
putenv("PBX_EFFECTUE=http://www.clubchancel.com/inc/e-transaction/response.php");
putenv("PBX_REFUSE=http://www.clubchancel.com/inc/e-transaction/response.php");
putenv("PBX_ANNULE=http://www.clubchancel.com/annulation.php");

# divers
//putenv("PBX_TXT=Connexion au serveur de paiement");
putenv("PBX_WAIT=0"); // en millisecondes
//putenv("PBX_BOUTPI=Paiement");
//putenv("PBX_BKGD=#F2F2F2"); // hexadecimal, color (red, white...) or file
putenv("PBX_OUTPUT=http://www.clubchancel.com/checkout.php4");
putenv("PBX_LANGUE=FRA");
putenv("PBX_ERREUR=http://www.clubchancel.com/inc/e-transaction/response.php");
putenv("PBX_TYPECARTE=AMEX");


# récupération des variables d'environnement

# indiquer au « modulev2.cgi » que les paramètres sont en variables d’environnement
getenv("PBX_MODE");
# identifiant de mon site
getenv("PBX_SITE");
getenv("PBX_RANG");
getenv("PBX_IDENTIFIANT");

# commande
getenv("PBX_TOTAL");
getenv("PBX_DEVISE");
getenv("PBX_CMD");
getenv("PBX_PORTEUR");
getenv("PBX_RETOUR");

# url de retour
getenv("PBX_EFFECTUE");
getenv("PBX_REFUSE");
getenv("PBX_ANNULE");

# divers
//getenv("PBX_TXT");
getenv("PBX_WAIT");
//getenv("PBX_BOUTPI");
//getenv("PBX_BKGD");
//getenv("PBX_OUTPUT");
getenv("PBX_LANGUE");
getenv("PBX_ERREUR");
getenv("PBX_TYPECARTE");

echo shell_exec("/var/www/vhosts/clubchancel.com/cgi-bin/modulev2.cgi");

?>