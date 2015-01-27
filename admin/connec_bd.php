<?php 
if(($_SERVER['HTTP_HOST']=="localhost")||($_SERVER['HTTP_HOST']=="127.0.0.1")||($_SERVER['HTTP_HOST']=="192.168.61.30"))
{
error_reporting(6);
	$host 	= 	"localhost"; 	// serveur
	$user 	= 	"root"; 		// utilisateur
	$pass 	= 	""; 			// vide en local
	$bdd 	= 	"club"; 			// nom de la BD
}
else
{
error_reporting(0);
	$host 	= 	"localhost"; 	// voir hébergeur
	$user 	= 	"club";	// utilisateur
	$pass 	= 	"cachou"; 	// mot de passe
	$bdd 	= 	"club"; 		// nom de la BDD
}

// connexion
$dbh = mysql_connect($host,$user,$pass) or die("Impossible de se connecter");
mysql_select_db("$bdd") or die("Impossible de selectionner la base");
?>