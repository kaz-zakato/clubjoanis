<?php include_once("header.php"); ?>


<br><span class="titre">Contacts</span><br>
<hr size="1" align="left" color="marroon" width="95%"><br>

<?php
require("inc/ps_login.php");
$ps_login = new ps_login;
$user = $ps_login->userInfos($vars["user_id"]); ?>

Votre commande � bien �t� enregistr�e :
un email de confirmation � �t� transmis � l'adresse <?php echo $user["user_email"]; ?>.

<br><br>

<a href="checkout.php?order_id=<?php echo $vars["order_id"] ?>&user_id=<?php echo $vars["user_id"] ?>&impr=1"
	target="new">Cliquez ici</a> pour imprimer votre r�capitulatif de commande.
<br>
(Votre facture vous sera adress�e avec votre colis.)

<br><br>

<?php include_once("footer.php"); ?>
