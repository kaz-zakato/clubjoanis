<?php
// Fichiers de configuration
require("../inc/common_vars.php");
require("../inc/global_vars.php");

error_reporting(E_ALL ^ E_NOTICE);

// Librairies
require("../inc/db_mysql.php");
require("../inc/ps_product_category.php");
require("../inc/ps_product.php");
require("../inc/ps_recipe.php");
require("../inc/ps_cart.php");
require("../inc/ps_orders.php");
$db 					= new DB;
$ps_product 			= new ps_product;
$ps_product_category 	= new ps_product_category;
$ps_cart 				= new ps_cart;
$ps_order 				= new ps_orders;
$ps_recipe				= new ps_recipe;

// Redirige l'utilisateur vers la page de login s'il n'est pas enregistré
if(!isset($_SESSION["auth"]["email"])){

	$_SESSION["order_shipping"] = 0;
	$_SESSION["lang"] 					= LANGUAGE;
	$_SESSION["cart"] 					= array();
	$_SESSION["auth"] 					= array();
	if(THIS_PAGE=="index"){
		header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/login.php");
		exit();
	}
}
$auth = $_SESSION["auth"];
?>

<html>
<head>
	<title><?php echo PAGETITLE ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<META NAME="Revisit-after" CONTENT="15 days">
	<meta name="ROBOTS" content="INDEX, FOLLOW">
	<meta name="keywords" content="">
	<meta name="description" content="">
	<META http-equiv="Content-Language" content="fr">
	<link rel="StyleSheet" href="../inc/style.css" type="text/css">
	<script src="../js/jquery-1.9.1.min.js"></script>
	<script src="../js/jssor.slider.mini.js"></script>
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

<body BGCOLOR="#F2F2F2" LEFTMARGIN="0" TOPMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">


<table width="1100" cellpadding="0" cellspacing="0" border="0" align="center">
<tr>
	<td background="../images/ombre_gauche.png"><img src="../images/blank.gif" width="10" height="1" border="0" alt=""></td>
	<td valign="top" align="left" colspan="2" height="155">
	<a href="index.php">
		<div id="bans">
			<div id="slides" u="slides" style="cursor: move; overflow: hidden; width: 1080px; height: 155px; position:absolute; left:0;">
		        <div><img u="image" src="../images/new_ban.png" width="1080" height="155" border="0" alt="Le Club Joanis" title="Le Club Joanis"/></div>
		        <div><img src="../images/new_ban_2.png" width="1080" height="155" border="0" alt="Le Club Joanis" title="Le Club Joanis"/></div>
		        <div><img src="../images/new_ban_3.png" width="1080" height="155" border="0" alt="Le Club Joanis" title="Le Club Joanis"/></div>
    		</div>
		</div>
	</a>
	</td>
	<td background="../images/ombre_droite.png"><img src="../images/blank.gif" width="10" height="1" border="0" alt=""></td>
</tr>
</table>

<table width="1100" border="0" cellspacing="0" cellpadding="0" align="center">
<tr class="content_block">
	<td width="10" background="../images/ombre_gauche.png"><img src="../images/blank.gif" width="10" height="1" border="0" alt=""></td>
	<td id="menu1" valign="top" class="menus" width="168" style="padding-left: 10px;background-image: url('../images/fond_milieu.png');color:white;  border-bottom: 1px solid white"><br>
	<a href='../index.php?list=1' style="margin-bottom:15px">Retour accueil</a><br><br><br>
	<a href='product.php?list=1' class='<?php if(THIS_PAGE=="product") echo "menuSelected"; else echo "menutitle"; ?>'>VINS</a><br>
	<?php
	/*****************************************************************************
	* LIST OF CONTENTS PRODUITS
	*****************************************************************************/
	if(THIS_PAGE=="product"){
		if(isset($_GET["list"]) || isset($_GET["product_id"])){
			print "<br>";
			
			// get list
			$where = " WHERE product_company_id = product_company AND product_flag = 1";
			if($_GET["list"] != "all")
			  $where .= " AND product_status>0";
			$req = "
				SELECT product_id, product_name, product_vintage, product_company, product_status
				FROM product, product_company $where ORDER BY product_ordre";
			$db->query($req);
	
			while($db->next_record()){
				$product_id 			= $db->f("product_id");
				$product_name 		= $db->f("product_name");
				$product_vintage 	= $db->f("product_vintage");
				$product_status 	= $db->f("product_status");
				switch ($product_status){
					case "0":
						$linkColor = "gray";
						break;
					default:
						$linkColor = "Black";
						break;
				}
				//print "<img src='../images/bullet.gif' width='' height='' hspace='3' align='absmiddle'>";
				print "<a href='product.php?product_id=$product_id' class='menu'";
				if($product_status == 0) print " style='color:#ff0000'";
				print ">$product_name";
				if($product_vintage) print "&nbsp;$product_vintage";
				print "</a><br>";
			}
			unset($product_id, $product_name, $product_vintage);
			
			//print "<img src='../images/bullet.gif' width='' height='' hspace='3' align='absmiddle'>";
			print "<br><a href='product.php?list=all' class='menu'>Vins Off-line</a><br>";
		}
	}
	?>
	<hr color="#4d4948" size="1" width="139">
	<a href='user.php' class='<?php if(THIS_PAGE=="user") echo "menuSelected"; else echo "menu"; ?>'>UTILISATEURS</a><br/>
	<hr color="#4d4948" size="1" width="139">
	<a href='orders.php?list=1' class='<?php if(THIS_PAGE=="orders") echo "menuSelected"; else echo "menu"; ?>'>COMMANDES</a><br/>
	<?php
	/*****************************************************************************
	* LIST OF CONTENTS COMMANDES
	*****************************************************************************/
	if(THIS_PAGE=="orders"){
		print "<br><img src='../images/bullet.gif' width='' height='' hspace='3' align='absmiddle'>";
		print "<a href=orders.php?status=99 class='menu'>Toutes</a><br>";
		for($i=0; $i<10; $i++){
			if($statusList = $ps_order->get_status($i)){
				print "<img src='../images/bullet.gif' width='' height='' hspace='3' align='absmiddle'>";
				print "<a href=orders.php?status=$i class='menu'>".str_replace(" ", "&nbsp;", $statusList[0])."</a><br>";
			}
		}
	}
	 ?>
	<hr color="#4d4948" size="1" width="139">
	<a href='home.php?content_id=1' class='<?php if(THIS_PAGE=="home") echo "menuSelected"; else echo "menu"; ?>'>EDITO</a><br><br>
	<?php
	if(THIS_PAGE=="home"){
	?>
		<a href='home.php?content_id=1' class='menu'><?php if($_GET["content_id"]==1) echo "<strong>Page d'accueil</strong>"; else echo "Page d'accueil"; ?></a><br>
	<?php
	}
	?>
	
	<img src="../images/blank.gif" border="0" height="50" width="158">
	</td>
	<td valign="top" style="padding-left: 10px; background-image: url('../images/fond_milieu.png');color:white; border-top:1px solid white;border-right:1px solid white; border-bottom: 1px solid white"><br>