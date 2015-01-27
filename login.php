<?php
// Fichiers de configuration
require("inc/common_vars.php");
require("inc/global_vars.php");

if(GENERATE_PAGE){
	//if (!file_exists(GENERATE_NAME) || filemtime(GENERATE_NAME)<(time()-GENERATE_TIME)){
		/*if($fp = fopen(URL."index.php?nosession=1", "r")) {
			while (!feof($fp)) {
		   	$indexHTML .= fgets($fp, 128);
			}
			fclose($fp);
		}

	  if (is_writable(GENERATE_NAME) && $handle = fopen(GENERATE_NAME, 'w')) {
	    fwrite($handle, $indexHTML);
	    fclose($handle);
	  }*/
	//}
}

// Librairies
require("inc/db_mysql.php");
$db 			= new DB;
require("inc/ps_login.php");
$ps_login = new ps_login;

// gestion des inscriptions
$user_email 		= $vars["user_email"];
$password   		= $vars["password"];
$passwordCheck  = $vars["passwordCheck"];
$user_first_name= $vars["user_first_name"];
$user_name      = $vars["user_name"];

if($user_email!=""){
	$err = $ps_login->checkUser($user_email, $password, $vars["redirect"]);
}

if($vars["recupPassword"]=="1"){
	$err = $ps_login->getPasswd($user_email);
}
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title><?php echo $titre_navigateur ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<META NAME="Revisit-after" CONTENT="15 days">
	<meta name="ROBOTS" content="INDEX, FOLLOW">
	<meta name="keywords" content="<?php echo $titre_navigateur ?>">
	<meta name="description" content="<?php echo $titre_navigateur ?>">
	<META http-equiv="Content-Language" content="fr">
	<link rel="StyleSheet" href="inc/style.css" type="text/css">
	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/jssor.slider.mini.js"></script>
	<script>
	    jQuery(document).ready(function ($) {
	        console.log(1);
	        var options = { $AutoPlay: true, $AutoPlayInterval : 7000, $SlideDuration : 1000 };
	        console.log(2);
	        var jssor_slider1 = new $JssorSlider$('bans', options);
	        console.log(3);
	        var slideDiv = $("#bans div");
	        console.log(4);
	        console.log(slideDiv);
	        console.log(5);
	        var offset = $("#menu1").offset();
	        console.log(6);
	        console.log(offset.left);
	        console.log(7);
	        console.log("Left : "+slideDiv.css("left"));
	        console.log(8);
	        $(slideDiv[0]).css("left",offset.left);
	        console.log(9);
	        console.log("Left : "+slideDiv.css("left"));
	        console.log(10);
	    });
	</script>
</head>

<body BGCOLOR="#F2F2F2" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">
<table width="1100" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
	<td background="images/ombre_gauche.png"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
	<td valign="top" align="left" colspan="2" height="155">
	<a href="index.php">
		<div id="bans">
			<div id="slides" u="slides" style="cursor: move; overflow: hidden; width: 1080px; height: 155px; position:absolute; left:0;">
		        <div><img u="image" src="images/new_ban.png" width="1080" height="155" border="0" alt="Le Club Joanis" title="Le Club Joanis"/></div>
		        <div><img src="images/new_ban_2.png" width="1080" height="155" border="0" alt="Le Club Joanis" title="Le Club Joanis"/></div>
		        <div><img src="images/new_ban_3.png" width="1080" height="155" border="0" alt="Le Club Joanis" title="Le Club Joanis"/></div>
    		</div>
		</div>
	</a></td>
	<td background="images/ombre_droite.png"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
</tr>
<tr class="content_block">
	<td background="images/ombre_gauche.png" width="10"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
	<td width="400" valign="middle" align="left" id="menu1" class="menu1" style="padding:7px;">
		<?php if($auth["user_id"]){ ?>
			<a href="account.php?profile=1" class="<?php if($profile==1) echo "menuHeaderSelected"; else echo "menuHeader"; ?>">Votre profil</a>
		<?php }else{ ?>
			<a href="login.php" class="menuHeader">Identifiez-vous</a>
		<?php } ?>&nbsp;|&nbsp;&nbsp;<a href="login.php?inscr=1" class="<?php if($inscr==1) echo "menuHeaderSelected"; else echo "menuHeader"; ?>">Inscription au Club Joanis</a><?php if($auth["user_id"]){ ?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="logout.php" class="menuHeader">Déconnexion</a><?php } ?>
	</td>
	<td class="menu1" valign="middle" align="right" style="padding:5px;"><a href="#" class="<?php if($_SESSION["cart"]) echo "menuHeaderSelected"; else echo "menuHeader"; ?>"
		onClick="
	  if(<?php echo sizeof($_SESSION["cart"]) ?>)
			document.location.href='cart.php<?php echo $urlSession ?>';
		else
		  alert('Votre panier est vide');
		">Votre panier</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="cgdv.php" class="<?php if(preg_match("/cgdv.php/", $PHP_SELF)) echo "menuHeaderSelected"; else echo "menuHeader"; ?>">Garanties</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="recettes.php" class="<?php if(preg_match("/recettes.php/", $PHP_SELF)) echo "menuHeaderSelected"; else echo "menuHeader"; ?>">Gastronomie</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="contact.php" class="<?php if(preg_match("/contact.php/", $PHP_SELF)) echo "menuHeaderSelected"; else echo "menuHeader"; ?>">Contacts</a></td>
	<td background="images/ombre_droite.png"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
</tr>
</table>

<table width="1100" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
	<td background="images/ombre_gauche.png" width="10"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
	<td valign="top" width="168" class="menus" style="background-image: url('images/fond_milieu.png');color:white;"><br><img src="images/blank.gif" width="9" height="10" border="0" align="absmiddle" alt=""><a href="index.php" class="menu">Accueil</a><br>

	<hr color="#4d4948" size="1" width="149" align="center">
	
	<img src="images/blank.gif" width="9" height="10" border="0" align="absmiddle" alt=""><a href="<?=$_SERVER['HTTP_REFERER']?>" class="menu">Retour</a><br><br>
	</td>
	<td valign="top" width="1" background="images/pointillets_v.gif"><img src="images/blank.gif" width="1" height="10" border="0" alt=""></td>
	<td valign="top" class="midlecontent" style="padding-top: 7px; padding-right: 10px; padding-left: 10px; border-right:1px solid white;">
		<?php if(isset($_GET["inscr"])){ ?>
			<span class="titre">Inscrivez-vous au Club Joanis</span>
			<br><br>
			<!--Ce Club est destiné à maintenir un lien personnel et privilégié entre les propriétaires du Domaine et les milliers de clients amateurs des vins de Val-Joanis et des jardins (nous exportons dans 76 pays).
			<br><br>
			En adhérant à ce Club vous recevrez des informations sur l'évolution des vins en cave et sur les vendanges. Vous serez egalement invités aux évènements organisés à Val Joanis : dégustations, visites, expositions...
			<br><br>-->
			Si vous n'êtes pas encore inscrit au club Joanis, vous pouvez devenir membre <strong>gratuitement</strong> en remplissant le formulaire ci-dessous : <br><br>
			Vous pourrez ainsi commander vos vins, champagnes et coffrets directement sur notre boutique.<br><br>
			Vous recevez également des invitations à venir découvrir en primeur les offres proposées ponctuellement sur clubjoanis.com.
		<?php }else{ ?>
			<span class="titre">Identifiez-vous</span>
			<br><br>
			Afin de compléter votre commande, veuillez vous identifier<br>
			<br>Si vous n'êtes pas encore inscrit au club Joanis, vous pouvez devenir membre, <strong>gratuitement</strong>, en
			<a href="login.php?inscr=1&redir=index.php">cliquant ici</a>
		<?php } ?>
		<br><br>

	<?php if($err) print "<p align='center'><font color=\"red\">" . $err . "</font></p><br>"; ?>
	<?php if(isset($_GET["inscr"])){ ?>
	<form name="subscribe" method="POST" action="login.php">
		<input type='hidden' name='redirect' value='<?php echo $_GET["redir"] ?>'>
		<input type='hidden' name='action' value='add'>
			<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="3"><img src="images/blank.gif" border="0" height="12" width="1"></td>
			</tr>
			<tr>
				<td width="60" align="left">&nbsp;Email&nbsp;:&nbsp;</td>
				<td width="5">&nbsp;</td>
				<td>
				<input type=text maxlength=64 name='user_email' value='<?php echo $user_email ?>' size=20 class=form>
				</td>
			</tr>
			<tr>
				<td colspan="3"><img src="images/blank.gif" border="0" height="3" width="1"></td>
			</tr>
			<tr>
				<td width="60" align="left">&nbsp;Nom&nbsp;:&nbsp;</td>
				<td width="5">&nbsp;</td>
				<td>
				<input type=text maxlength=64 name='user_first_name' value="<?php echo $user_first_name ?>" size=20 class=form>
				</td>
			</tr>
			<tr>
				<td colspan="3"><img src="images/blank.gif" border="0" height="3" width="1"></td>
			</tr>
			<tr>
				<td width="60" align="left" valign="top">&nbsp;Prénom&nbsp;:&nbsp;</td>
				<td width="5">&nbsp;</td>
				<td>
				<input type=text maxlength=64 name='user_name' value="<?php echo $user_name ?>" size=20 class=form>
				<br><br>
				<input type="submit" name="Mail.x" value=" Créer mon compte " class="Bsbttn">
				</td>
			</tr>
			<tr>
				<td colspan="3"><img src="images/blank.gif" border="0" height="12" width="1"></td>
			</tr>
			</table>
	</form>
	
	<?php }else{ ?>
  
	<form name="member" method="POST" action="login.php">
		<input type='hidden' name='redirect' value='<?php echo $_GET["redir"] ?>'>
		<input type='hidden' name='action' value='reg'>
			<table width="320" border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td colspan=3><img src="images/blank.gif" border="0" height="12" width="1"></td>
			</tr>
			<tr>
				<td valign="top" align="left">&nbsp;Email&nbsp;:&nbsp;</td>
				<td>&nbsp;&nbsp;</td>
				<td align="left">
				<input type=text maxlength=64 name='user_email' value='<?php echo $user_email ?>' size=20 class=form>
				</td>
			</tr>
			<tr>
				<td colspan=3><img src="images/blank.gif" border="0" height="12" width="1"></td>
			</tr>
			<tr>
				<td valign="top" align="left">&nbsp;Mot&nbsp;de&nbsp;passe&nbsp;:&nbsp;</td>
				<td>&nbsp;</td>
				<td align="left">
				<input type="password" maxlength="14" name="password" value="" size="20" class=form> <input type="submit" name="Mail.x" value=" Entrez " class="Bsbttn">
				<div class="small" style="color:white;">
				<a href="#" onClick="
					  if(email = prompt('Veuillez saisir votre adresse email dans le champ ci-dessous et Cliquez sur OK.', ''))
							document.location.href = 'login.php<?php echo $urlSession ?>&user_email='+email+'&recupPassword=1';
					">&nbsp;Vous avez oublié votre mot de passe ?</a></div>
				</td>
			</tr>
			<tr>
				<td colspan=3><img src="images/blank.gif" border="0" height="12" width="1"></td>
			</tr>
			</table>
	</form>
	<?php }
	
include_once("footer.php"); ?>