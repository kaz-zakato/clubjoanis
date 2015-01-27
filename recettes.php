<?php include_once("header.php");

$queryEdito="SELECT content_home FROM contents WHERE content_id='2'";

$db->query($queryEdito);
$db->next_record();

$edito=$db->f("content_home");

if(!$idRec && $edito){
	//echo "<br><span class=\"titre\">Edito</span>";
	//echo "<br><br>".nl2br($edito);
	echo "<br><br>".nl2br($edito);
	$img_recipe	= "images/recipes/default_recipe.jpg";
}
else{
	$queryRecipe = "SELECT * FROM gastronomie WHERE enligne='Y'";
	if($idRec) $queryRecipe .= " AND recette_id='$idRec'";
	else $queryRecipe .= " ORDER BY recette_id DESC";
	//echo $queryRecipe;
	
	$db->query($queryRecipe);
	$db->next_record();
	
	$idRec			= $db->f("recette_id");
	$titre 			= stripslashes($db->f("titre"));
	$nbrPers		= $db->f("nbr_pers");
	$prepaTime		= stripslashes($db->f("temps_prepa"));
	$cookingTime	= stripslashes($db->f("temps_cuisson"));
	$ingredients	= stripslashes($db->f("ingredients"));
	$preparation	= nl2br(stripslashes($db->f("preparation")));
	$accord_vin		= $db->f("accord_vin");
	
	$img_recipe			= $db->f("recipe_img");
	if($img_recipe && file_exists("images/recipes/$img_recipe")){
		$img_recipe = "images/recipes/$img_recipe";
	}
	else{
		$img_recipe	= "images/recipes/default_recipe.jpg";
	}
	?>
	<span class="titre">Gastronomie</span>
	<br><br>
	<?php
	echo "<strong class=\"stitre\" style=\"text-transform: uppercase;\">$titre</strong>";
	if($nbrPers) echo "<br /><br />Pour <strong>$nbrPers</strong> personne(s)";
	if($prepaTime) echo "<br />Temps de préparation : <strong>$prepaTime</strong>";
	if($cookingTime) echo "<br />Temps de cuisson : <strong>$cookingTime</strong>";
	echo "<br /><br /><span class=\"stitre\">Ingrédients :</span><br />$ingredients";
	echo "<br /><br /><span class=\"stitre\">Préparation :</span><br />$preparation";
	
	if($accord_vin){
		$list  =
		"SELECT
			p.product_id, p.product_name, p.product_vintage, p.product_company, p.product_region,
			p.product_desc, p.product_full_img, p.product_type, c.product_company_nom, c.product_company_id,
			r.product_region_nom, r.product_region_id
			FROM product p, product_company c, product_region r
			WHERE p.product_id='$accord_vin' AND p.product_region = r.product_region_id AND p.product_company = c.product_company_id";
		
		$db->query($list);
		$db->next_record();
		
		$idProd			= $db->f("product_id");
		if($db->f("product_vintage")=="0"){
			$vintage	= "";
		}else{
			$vintage	= " ".$db->f("product_vintage");
		}
		$company		= $db->f("product_company");
		$label 			= $db->f("product_name") . $vintage;
		$region			= $db->f("product_region_nom");
		$type			= strtolower($db->f("product_type"));
		$photo		= "images/btles/".$db->f("product_full_img")."\"";
		$desc			= $db->f("product_desc");
		if($db->f("product_full_img")=="" || !file_exists("images/btles/".$db->f("product_full_img"))){
			$photo	= "images/blank.gif\" width=\"1\"";
		}
		?>
		
		<br><br>
		<span class="stitre">Accords mets et vin :</span>
		<br>
		<table border="0" cellpadding="2" cellspacing="0">
		<tr>
		<td valign="top" width="320">
			<table border="0" cellpadding="0" cellspacing="0">
			<form name="form" action="cart.php">
				<input type=hidden value="product_list" name="page">
				<input type=hidden value="<?php echo $_GET["dom"] ?>" name="dom">
				<input type=hidden value="<?php echo $_GET["type"] ?>" name="type">
				<input type=hidden value="<?php echo $_GET["category_id"] ?>" name="category_id">
				<input type=hidden value="<?php echo $region ?>" name="region">
				<input type=hidden value="addCart" name="func">
				<input type=hidden name="label" value="<?php echo "$label"; ?>">
				<input type=hidden name="company" value="<?php echo $company; ?>">
				<input type="hidden" value="<?php echo $idProd ?>" name="product_id">
				<input type="hidden" value="<?php echo $idProd ?>" name="product">
				<input type="hidden" value="<?php echo $price_min_qty; ?>" name="quantity">
			</form>
			<tr>
				<td><br>
					<img border="0" height="255" align="left" style="padding-right: 9px;" src="<?php echo $photo; ?>>
					<strong><?php echo $label; ?></strong><br>
					<strong><?php echo $region; ?></strong><br><br>
					<?php if($desc)	echo nl2br($desc)."<br><br>"; ?>
		
					<?php
					$price = $ps_product->get_price($db->f("product_id"));
					if ($price){
						echo "<strong>Prix :</strong> ";
						
						if($price["product_promo"]!=0){
							echo "<strike><em>" . number_format($price["product_price"], 2, ',', ' ') . "</em></strike>&nbsp;&nbsp;" . number_format($price["product_promo"], 2, ',', ' ');
						}
						else echo number_format($price["product_price"], 2, ',', ' ');
						
						echo " € TTC /btle";
						$s = ($price["product_min_qty"]>1) ? "s" : "";
						if($price["product_min_qty"]>1) echo " (par ".$price["product_min_qty"]. " bouteille$s)";
					} else {
						echo "<strong>Prix :</strong> N.C.";
					}
					?>
					<br><br><a href="product.php?product=<?php echo $idProd;  ?>">En savoir plus</a>&nbsp;|&nbsp;<a href="javascript:document.form.submit();">Ajouter au panier</a>
					<br><br>
				</td>
			</tr>
			</table>
		</td>
		</tr>
		</table>
	<?php
	}
}
	?>
<br><br>

<?php include_once("footer.php"); ?>
