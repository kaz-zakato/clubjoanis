<?php
// Fichiers de configuration
require("inc/common_vars.php");
require("inc/global_vars.php");

// Librairies
require("inc/db_mysql.php");
require("inc/ps_product.php");
require("inc/ps_cart.php");
$db 			= new DB;
$ps_product 	= new ps_product;
$ps_cart 		= new ps_cart;

// actions du panier
$ok = false;
if(isset($vars["func"])){

	switch($vars["func"]){
	  case "addCart":
	  $ok = $ps_cart->add($vars);
	  break;

	  case "cartUpdate":
	  $ps_cart->update($vars);
	  break;

	  case "cartDelete":
	  $ps_cart->delete($vars);
	  break;

	  case "cartReset":
	  $ps_cart->reset();
	  break;
	}
}

if($_SERVER["REMOTE_ADDR"]=='195.200.189.230'){
	//print_r($_SESSION);
}

// Redirige l'utilisateur vers la page de login s'il n'est pas enregistré
if(!isset($_SESSION["auth"]["user_id"]) && (THIS_PAGE=="account" || THIS_PAGE=="checkout")){
	header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) .
		"/login.php$urlSession&redir=".THIS_PAGE);
	exit();
}

// Gestion des sessions utilisateurs
if(!isset($_SESSION["auth"]["perms"])){

	$_SESSION["order_shipping"] = 0;
	$_SESSION["order_discount"] = 0;
	$_SESSION["lang"] 					= LANGUAGE;
	$_SESSION["cart"] 					= array();
	$_SESSION["auth"] 					= array();
	$_SESSION["auth"]["perms"]  = 1;
	$_SESSION["auth"]["user_id"]= 0;

	$db->query("select * from contents where content_id=1");
	$_SESSION["content"] = $db->next_array();

}
$auth = $_SESSION["auth"];

// gestion des titres et des meta tags 

if ($_GET["type"]) {
	$q  = " p.product_type='".$_GET["type"]."'";
	$url = "&type=".$_GET["type"];
	$typeTitre = "Tous nos vins ".$_GET["type"]."s";
}
if (isset($_GET["product"])) {
	include_once("product_select.php");
}
if (THIS_PAGE=="index" || THIS_PAGE=="product_list") $titre_navigateur = "Le Club Joanis - achat, vin, luberon, provence, chateauneuf du pape, champagne, camargue, gigondas, vacqueyras, ventoux, var, rhone, vins";
else $titre_navigateur = "Le Club Joanis - ".stripslashes(stripslashes($product_name." ".$typeTitre));
//else $titre_navigateur = "Le Club Chancel - ".stripslashes(stripslashes($product_name." ".$typeTitre." ".$category_id." ".$_GET["dom"]));
//else $titre_navigateur = "Le Club Chancel";
if($product_desc){
	$metaDescription = stripslashes(substr($product_desc, 0, 200));
	$tag = preg_quote("<br />","/");
	$metaDescription = preg_replace("/$tag|\r\n/", " ", $metaDescription);
	$metaDescription = preg_replace("/\"/", "", $metaDescription);
}
else{
	$metaDescription = "Bienvenue sur la boutique du Club Joanis. L'inscription est gratuite et sans engagements. Le Club vous offre une sélection de vinsn du chateau Val Joanis (www.val-joanis.com).";
}
if(THIS_PAGE=="cart"){
//if(THIS_PAGE=="cart" || strpos(THIS_PAGE,"product")!==false){
	//$titre_menu = "Nos Vins > ".$typeTitre." ".$category_id." ".$_GET["dom"];
	$titre_menu = "Nos Vins > ".$typeTitre." ".$category_id." ".$_GET["dom"];
	$titre_navigateur .= "Votre panier";
}else if (THIS_PAGE=="cgdv"){
  $titre_menu = "Garanties";
  $titre_navigateur .= "Conditions générales de vente";
}else if (THIS_PAGE=="contact"){
  $titre_menu = "Contacts";
  $titre_navigateur .= "Contacts";
}else if (strpos(THIS_PAGE,"recettes")!==false){
  $titre_menu = "Gastronomie";
  $titre_navigateur .= "Gastronomie";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta http-equiv="content-Language" content="fr" />
	<meta name="language" content="fr" />
	<title><?php echo $titre_navigateur ?></title>
	<meta name="keywords" lang="fr" content="achat, vin, luberon, provence, saint andrieu, chateauneuf du pape, champagne, camargue, gigondas, vacqueyras, ventoux, var, rhone, vins, huiles d'olive, délices, coffrets, cadeaux, cave, sommelier, moutarde, tapenade, rhône, chateau val joanis, joanis, trapadis, valcombe, autard, andrieu" />
	<meta name="description" lang="fr" content="<?php echo $metaDescription; ?>" />
	<meta name="copyright" content="(c)2006 Château Val Joanis" />
	<meta name="identifier-URL" content="http://www.clubjoanis.com" />
	<meta name="Revisit-after" content="15 days" />
	<meta name="ROBOTS" content="INDEX, FOLLOW" />
	<meta name="Author" lang="fr" content="Le studio vinternet internet extranet site commerce catalogue électronique conception création réalisation production multimédia hébergement référencement promotion publicité animation VINTERNET" />
	<?php
	if ($_GET["impr"]=="1"){
	  print '<link rel="StyleSheet" href="inc/style1.css" type="text/css">';
	}else{
	  print '<link rel="StyleSheet" href="inc/style.css" type="text/css">';
	}
	?>
	<script src="js/jquery-1.9.1.min.js"></script>
	<script src="js/jssor.slider.mini.js"></script>
	<script>
	    jQuery(document).ready(function ($) {
	        var options = { $AutoPlay: true, $AutoPlayInterval : 7000, $SlideDuration : 1000 };
	        var jssor_slider1 = new $JssorSlider$('bans', options);
	        var slideDiv = $("#bans div");
	        console.log(slideDiv);
	        var offset = $("#menu1").offset();
	        console.log(offset.left);
	        console.log("Left : "+slideDiv.css("left"));
	        $(slideDiv[0]).css("left",offset.left);
	        console.log("Left : "+slideDiv.css("left"));
	    });
	</script>
</head>

<body LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">

<?php
if ($_GET["impr"]=="1"){
	print '<table width="650" cellpadding="0" cellspacing="3" border="0">
		<tr><td valign="top">';
	print '<div align="center" class="titre"><br>Club Joanis - Château Val Joanis</div><br>';
	return;
}
?>
<div style="position: absolute; visibility: hidden">achat vin vins luberon provence chateauneuf du pape champagne</div>
<table width="1100" cellpadding="0" cellspacing="0" border="0" align="center">
<!-- <tr>
	<td background="images/bckg_line_left_top.gif" width="10"><img src="images/blank.gif" width="10" height="16" border="0" alt=""></td>
	<td height="16" bgcolor="#FF9C31">
	&nbsp;&nbsp;&nbsp;<span class="small"><a href="http://www.clubchancel.com" style="color:white; text-decoration:none;"><strong>www.clubchancel.com</strong></a></span>
	</td>
	<td height="16" bgcolor="#FF9C31" align="right">
	<?php if($auth["user_id"]) echo "<strong style=\"color: white;\">".$auth["user_gender"] . " " . $auth["first_name"] . " " . $auth["last_name"]."</strong>"; ?>&nbsp;&nbsp;&nbsp;
	</td>
	<td background="images/bckg_line_right_top.gif" width="10"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
</tr> -->
<tr>
	<td background="images/ombre_gauche.png" width="10"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
	<td height="1" colspan="2"><img src="images/blank.png" width="10" height="1" border="0" alt=""></td>
	<td background="images/ombre_droite.png" width="10"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
</tr>

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
	</a>
	</td>
	<td background="images/ombre_droite.png" width="10"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
</tr>
<tr class="content_block">
	<td background="images/ombre_gauche.png" width="10"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
	<td id="menu1" width="400" valign="middle" align="left" class="menu1" style="padding:7px;">
		<?php if($auth["user_id"]){ ?>
			<a href="account.php?profile=1" class="<?php if($profile==1) echo "menuHeaderSelected"; else echo "menuHeader"; ?>">Votre profil</a>
		<?php }else{ ?>
			<a href="login.php" class="menuHeader">Identifiez-vous</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="login.php?inscr=1" class="<?php if($inscr==1) echo "menuHeaderSelected"; else echo "menuHeader"; ?>">Inscription au Club Joanis</a>
		<?php }
			  if($auth["user_id"]){ ?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="logout.php" class="menu">Déconnexion</a><?php } ?>
	</td>
	<td class="menu1" valign="middle" align="right" style="padding:5px;"><a href="#" class="<?php if(preg_match("/cart.php/", $PHP_SELF)) echo "menuHeaderSelected"; else echo "menuHeader"; ?>"
		onClick="
	  if(<?php echo sizeof($_SESSION["cart"]) ?>)
			document.location.href='cart.php<?php echo $urlSession ?>';
		else
		  alert('Votre panier est vide');
		">Votre panier</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="cgdv.php" class="<?php if(preg_match("/cgdv.php/", $PHP_SELF)) echo "menuHeaderSelected"; else echo "menuHeader"; ?>">Garanties</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="contact.php" class="<?php if(preg_match("/contact.php/", $PHP_SELF)) echo "menuHeaderSelected"; else echo "menuHeader"; ?>">Contacts</a></td>
	<td background="images/ombre_droite.png" width="10"><img src="images/blank.gif" width="10" height="1" border="0" alt=""></td>
</tr>
</table>

<table width="1100" cellpadding="0" cellspacing="0" border="0" align="center">
<tr >
	<td background="images/ombre_gauche.png" width="10"><img src="images/blank.png" width="10" height="1" border="0" alt=""></td>
	<td valign="top" width="168" class="menus" style="background-color: #342925;color:white;">
		<br/>
		<img src="images/<?php if(!$type && !$dom && !$category_id && preg_match("/index.php/", $PHP_SELF)) echo "fleche_menu.gif"; else echo "blank.gif"; ?>" width="9" height="10" border="0" align="absmiddle" alt="">&nbsp;<a href="index.php" class="<?php if(!$type && !$dom && !$category_id && preg_match("/index.php/", $PHP_SELF)) echo "menuSelected"; else echo "menu"; ?>">Accueil</a><br>

	<?php // affichage de "tous nos vins" ?>
	<hr color="#4d4948" size="1" width="149" align="center">
	<div class="menutitle">&nbsp;&nbsp;&nbsp;&nbsp;Nos vins<div>
	<img src="images/<?php if($type=="Blanc") echo "fleche_menu.gif"; else echo "blank.gif"; ?>" width="9" height="10" border="0" align="absmiddle" alt="">&nbsp;<a href="product_list.php?type=Blanc" class="<?php if($type=="Blanc") echo "menuSelected"; else echo "menu"; ?>">Tous nos vins blancs</a><br>
	<img src="images/<?php if($type=="Rosé") echo "fleche_menu.gif"; else echo "blank.gif"; ?>" width="9" height="10" border="0" align="absmiddle" alt="">&nbsp;<a href="product_list.php?type=Rosé" class="<?php if($type=="Rosé") echo "menuSelected"; else echo "menu"; ?>">Tous nos vins rosés</a><br>
	<img src="images/<?php if($type=="Rouge") echo "fleche_menu.gif"; else echo "blank.gif"; ?>" width="9" height="10" border="0" align="absmiddle" alt="">&nbsp;<a href="product_list.php?type=Rouge" class="<?php if($type=="Rouge") echo "menuSelected"; else echo "menu"; ?>">Tous nos vins rouges</a><br>
</td>
<td class="midlecontent" valign="top" style=" <?php if(!preg_match("/home/", $CurrentPage)){ echo "border-right:1px solid white;";}?>">
