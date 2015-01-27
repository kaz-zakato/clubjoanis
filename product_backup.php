<?php
include_once("header.php");

/*******************************************************************************
* function printif(
*	string string[, string label, int nbbr_afer, int nbbr_before]
*	)
*******************************************************************************/
function printif($string, $label="", $nbbr_afer=0, $nbbr_before=0){

	if($nbbr_afer){
		for($i=0;$i<$nbbr_afer;$i++) $br_afer .= "<br />";
	}
	if($nbbr_before){
		for($i=0;$i<$nbbr_before;$i++) $br_before .= "<br />";
	}

	if ($string){
	  $string = $br_before.'<span class="stitre">'.$label.'</span>'.$string.$br_afer;
	  print nl2br($string);
	}
}
?>

<table border="0" cellpadding=0 cellspaging=0 width=100%>
<tr><td valign="top">

<div style="width:97%; text-align: left;" align="left">
<br><span class="titre"><?php echo $product_name; if($product_vintage) echo " $product_vintage"  ?></span>
<?php if($product_region_nom != "Aucun"){ ?><br><span class="stitre"><?php echo "$product_region_nom $product_type" ?></span><?php } ?><br><br><br>
<?php
if($product_full_img!="" && file_exists("images/btles/".$product_full_img)){
	$photo = $dir.$product_full_img;
	echo "<img border=0 align=\"left\" style=\"margin-right: 9px;\" src=\"$photo\">";
}
printif($product_desc, "", 2);
printif($product_sdesc, "Vinification : <br>", 2);
printif($product_grapes, "Cépages : <br>", 2);
//printif($product_situation, "Situation : <br>", 2);
printif($product_render, "Rendement : <br>", 2);
printif($product_degree, "Degrés :  <br>", 2);
printif($product_sdesc2, "Critiques : <br>", 2);
printif($product_medals, "Récompenses : <br>", 2);
if($product_link != "")
{
	echo "<span class=\"stitre\">Pour en savoir plus : </span>";
	echo "<a href=\"".$product_link."\" target=\"_blank\">".$product_link."</a>";
	echo "<br><br>";
}
?>

</div>
<?php if($product_vintage != 0) $product_vintag = $product_vintage;
   else $product_vintag = ""; ?>

<form name="form" action="cart.php">
	<input type=hidden value="product_list" name="page">
	<input type=hidden value="<?php echo $_GET["dom"] ?>" name="dom">
	<input type=hidden value="<?php echo $_GET["type"] ?>" name="type">
	<input type=hidden value="<?php echo $_GET["category_id"] ?>" name="category_id">
	<input type=hidden value="<?php echo $product_region_nom; ?>" name="region">
	<input type=hidden value="addCart" name="func">
	<input type=hidden name="label" value="<?php echo "$product_name $product_vintag"; ?>">
	<input type=hidden name="company" value="<?php echo $product_company_nom; ?>">
	<input type="hidden" value="<?php echo $vars["product"] ?>" name="product_id">
	<input type="hidden" value="<?php echo $vars["product"] ?>" name="product">
	<input type="hidden" value="<?php echo $price_min_qty; ?>" name="quantity">
</form>


<div align="center"><br>
Prix TTC : <?php
$price = $ps_product->get_price($vars["product"]);
if ($price){
	
	if($price["product_promo"]!=0){
		echo "<strike><em>" . number_format($price["product_price"], 2, ',', ' ') . "</em></strike>&nbsp;&nbsp;<strong>" . number_format($price["product_promo"], 2, ',', ' ');
	}
	else echo "<strong>" . number_format($price["product_price"], 2, ',', ' ');
	if($_GET["range"] == 9 || $product_region_nom == "Aucun") $btle = "";
	else $btle = " / btle";
	echo " € $btle";
	if($product_vol == "") echo "" ; else echo " ($product_vol cl)";
	echo "</strong>";
	$s = ($price["product_min_qty"]>1) ? "s" : "";
	if($price["product_min_qty"]>1) echo "<br>(par ".$price["product_min_qty"]. " bouteille$s)";
} else {
	echo "N.C.";
}
?>
<br><br>
<img align="absmiddle" value="Ajouter" border="0" src="images/ajouter.gif" style="cursor: pointer;" hspace=35 onClick="document.form.submit()">
</div>

</td></tr></table>
<br><br>

<?php include("footer.php") ?>
