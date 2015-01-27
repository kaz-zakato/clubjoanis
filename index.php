<?php
$CurrentPage = "home";
include_once("header.php");

if($auth["user_id"]){ ?>
	<b>Bienvenue <?= $auth["first_name"] . " " . $auth["last_name"] ?></b><br>
	<span class="small">Si vous n'êtes pas <?= $auth["first_name"] . " " . $auth["last_name"] ?>,
	<a href="logout.php">cliquez ici</a></span><br>
<?php }


$list  =
"SELECT
	p.product_id, p.product_name, p.product_vintage, p.product_company, p.product_region,
	p.product_desc, p.product_full_img, p.product_type, r.product_region_nom, r.product_region_id,
	c.product_company_nom, c.product_company_id
	FROM product p, product_region r, product_company c
	WHERE p.product_home='Y' AND p.product_company = c.product_company_id AND p.product_region = r.product_region_id";

$db->query($list);
$db->next_record();

$idProd			= $db->f("product_id");
if($db->f("product_vintage")=="0"){
	$vintage	= "";
}else{
	$vintage	= " ".$db->f("product_vintage");
}
$company		= $db->f("product_company_nom");
$label 			= $db->f("product_name") . $vintage;
$region			= $db->f("product_region_nom");
$type			= strtolower($db->f("product_type"));
$photo		= "images/btles/".$db->f("product_full_img")."\"";
$desc			= $db->f("product_desc");
if($db->f("product_full_img")=="" || !file_exists("images/btles/".$db->f("product_full_img"))){
	$photo	= "images/blank.gif\" width=\"1\"";
}
?>

<br>

<table border="0" cellpadding="2" cellspacing="0">
<tr>
<td valign="top" width="320">
	<table border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td class="titre"><?php echo $label; ?></td>
	</tr>
	<tr>
	<tr>
		<td class="stitre"><?php echo $region; ?></td>
	</tr>
			
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
				<input type="hidden" value="6" name="quantity">
			</form>
	<tr>
		<td>
			<img border="0" align="left" style="padding-right: 9px;" src="images/logocvj_transparence_350x350_6.png">
		</td>
	</tr>
	</table>
</td>
</tr>
</table>

<?php include_once("footer.php"); ?>
