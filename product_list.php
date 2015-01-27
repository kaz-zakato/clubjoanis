<?php
include_once("header.php");

// Enable the multi-page search result display
if (empty($offset)) $offset=0;

$category_id = $_GET["category_id"];
$category_range = $_GET["range"];
$category_dom = $_GET["dom"];


if($_GET["category_id"])
{
	$sql_type = "SELECT product_region_nom FROM product_region WHERE product_region_id = '$category_id'";
	$db->query($sql_type);
	$db->next_record();
	$titre = $db->f("product_region_nom");
}
if($_GET["range"])
{
	$sql_type = "SELECT product_company_nom FROM product_company WHERE product_company_id = '$category_range'";
	$db->query($sql_type);
	$db->next_record();
	$titre = $db->f("product_company_nom");
}
if($_GET["dom"])
{
	$sql_type = "SELECT product_company_nom FROM product_company WHERE product_company_id = '$category_dom'";
	$db->query($sql_type);
	$db->next_record();
	$titre = $db->f("product_company_nom");
}


if (is_int($category_id)) {
	$categories = "
		RIGHT JOIN product_category pc on p.product_id=pc.product_id
		RIGHT JOIN category c on pc.category_id=c.category_id ";
}
if($_GET["range"])
{
	$list  =
		"SELECT distinct
		p.product_id, p.product_name, p.product_vintage, p.product_company, p.product_region,
		p.product_desc, p.product_etiq, p.product_type, p.product_small_img, c.product_company_id, c.product_company_nom
		FROM product p $categories,  product_company c
		WHERE c.product_company_id = p.product_company AND";
	$count =
		"SELECT count(*) as num_rows
		FROM product p,  product_company c
		WHERE c.product_company_id = p.product_company AND";
}
else
{	
	$list  =
		"SELECT distinct
		p.product_id, p.product_name, p.product_vintage, p.product_company, p.product_region,
		p.product_desc, p.product_etiq, p.product_type, p.product_small_img, r.product_region_id, 
		r.product_region_nom, r.product_region_ordre, c.product_company_id, c.product_company_nom
		FROM product p $categories, product_region r, product_company c
		WHERE r.product_region_id = p.product_region AND c.product_company_id = p.product_company AND";
	$count =
		"SELECT count(*) as num_rows
		FROM product p, product_region r, product_company c
		WHERE r.product_region_id = p.product_region AND c.product_company_id = p.product_company AND";
}
/*$count =
	"SELECT count(*) as num_rows
	FROM product p, product_region r
	WHERE p.product_name = '$category_id' AND";*/
// Check to see if this is a search or a browse by category
// Default is to show all products
/*if (is_int($category_id)) {
	$q  = "(pc.category_id='$category_id' OR c.category_parent='$category_id') ";
	$url = "&category_id=$category_id";
}else if ($category_id!="") {
	$q  = " r.product_region_nom='$category_id' ";
	$url = "&category_id=".urlencode($category_id);
}else if ($_GET["type"]) {
	$q  = " p.product_type='".$_GET["type"]."'";
	$url = "&type=".$_GET["type"];
	$typeTitre = "Tous nos vins ".$_GET["type"]."s";
}
else if ($_GET["range"]) {
	$q  = " c.product_company_nom='".$_GET["range"]."'";
	$url = "&range=".$_GET["range"];
	$typeTitre = $_GET["range"];
	if(!ereg("x$|s$", $_GET["range"])) $typeTitre .= "s";
}else if ($_GET["dom"]) {
	$q  = " c.product_company_nom='".$_GET["dom"]."'";
	$url = "&dom=".$_GET["dom"];
} else {
	$q = " 1=1 ";
}*/
if (is_int($category_id)) {
	$q  = "(pc.category_id='$category_id' OR c.category_parent='$category_id') ";
	$url = "&category_id=$category_id";
}else if ($category_id!="") {
	$q  = " r.product_region_id='$category_id' ";
	$url = "&category_id=".urlencode($category_id);
}else if ($_GET["type"]) {
	$q  = " p.product_type='".$_GET["type"]."' AND r.product_region_id != 12";
	$url = "&type=".$_GET["type"];
	$typeTitre = "Tous nos vins ".$_GET["type"]."s";
}
else if ($_GET["range"]) {
	$q  = " c.product_company_id='".$_GET["range"]."'";
	$url = "&range=".$_GET["range"];
	$typeTitre = $_GET["range"];
	if(!preg_match("/x$|s$/", $_GET["range"])) $typeTitre .= "s";
}else if ($_GET["dom"]) {
	$q  = " c.product_company_id='".$_GET["dom"]."'";
	$url = "&dom=".$_GET["dom"];
} else {
	$q = " 1=1 ";
}

$q .= " AND p.product_status>0 AND p.product_status<=".$auth["perms"];

//$list .= $q . " ORDER BY p.product_name LIMIT $offset, " . SEARCH_ROWS;
$list .= $q . " ORDER BY p.product_ordre LIMIT $offset, " . SEARCH_ROWS;
$count .= $q;

$db->query($count);
$db->next_record();
$num_rows = $db->f("num_rows");
?>

<?php /*<span class="titre"><?php echo stripslashes($titre.$typeTitre.$_GET["dom"]); ?></span><br><br> <?php */ ?>
<span class="titre"><?php echo stripslashes($titre); ?></span><br><br>


<?php
if ($num_rows == 0) {
	echo "<br>Aucun r&eacute;sultat trouv&eacute;.<BR>";
	include_once("footer.php");
	return;
}
else echo "<table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\">";

$db->query($list);
$i = 50;

while ($db->next_record()){
	if($db->f("product_vintage")=="0"){
		$vintage = "";
	}else{
		$vintage = $db->f("product_vintage");
	}
									$label 			= $db->f("product_name");
	if($_GET["range"] != 9)	$region			= $db->f("product_region_nom");
	if($_GET["range"] != 9)	$type			= strtolower($db->f("product_type"));
	//$etiquette		= $db->f("product_etiq");
									$thumbnail		= $db->f("product_small_img");
									$desc			= $db->f("product_desc");
	?>

		<tr><td valign="top" align="center"><a href="product.php?product=<?php $db->p("product_id"); echo $url ?>">
		<?php
		/*if($etiquette!="" && file_exists("images/etiqs/".$etiquette)){
			$etiquette = "images/etiqs/".$etiquette;
			echo "<img border=0 width=120 align=\"left\" style=\"margin-right: 9px;\" src=\"$etiquette\">";
		}
		else{*/
			if($thumbnail!="" && file_exists("images/btles/mini/".$thumbnail)){
				$thumbnail = "images/btles/mini/".$thumbnail;
				echo "<img border=0 align=\"left\" style=\"margin-right: 9px;\" src=\"$thumbnail\">";
			}
		//}
		?></a></td>

		<form action="cart.php" name="form<?php $db->p("product_id") ?>">
		<td valign="bottom">

		<b><a href="product.php?product=<?php $db->p("product_id"); echo $url ?>"></b>
		<?php echo "$label</a></b>";
		if(!$_GET["range"])echo "<br>$region $type"; ?>

		<?php if($desc){ 	echo "<br><br>".nl2br($desc); } ?>
		<?php if($cepages){ echo "<br>$cepages"; } ?><br><br>

		<?php
		$price = $ps_product->get_price($db->f("product_id"));
		if ($price){
			if($_GET["range"]) $btle = "";
			else $btle = " / btle";
			echo "<span class='price'>". number_format($price["product_price"], 2, ',', ' ') . " € TTC $btle</span>";
			$s = ($price["product_min_qty"]>1) ? "s" : "";
			if($price["product_min_qty"]>1) echo " (par ".$price["product_min_qty"]. " bouteille$s)";
		} else {
			echo "N.C.";
		}
		?>

		<br><br>
		<input type=hidden value="<?php echo THIS_PAGE; ?>" name="page">
		<input type=hidden value="<?php echo $category_id; ?>" name="category_id">
		<input type=hidden value="<?php echo $region; ?>" name="region">
		<input type=hidden value="addCart" name="func">
		<input type=hidden name="label" value="<?php echo $label; ?>">
		<input type=hidden name="company" value="<?php echo $db->p("product_company_nom"); ?>">
		<input type="hidden" value="<?php $db->p("product_id") ?>" name="product_id">
		<input type="hidden" value="<?php echo $price_min_qty; ?>" name="quantity">

		<img src="images/ajouter.gif" onClick="document.form<?php $db->p("product_id") ?>.submit()" value="Ajouter" border="0" style="cursor: pointer;">
    	</td>
    </form>
	</tr>

    <tr><td><img src="images/blank.gif" width="1" height="15" border="0" alt=""></td></tr>

	<?php
	$i = $i+250;
} //end while
?>

</table>
<br><br>

<div align="center">
<!-- Build previous/next navigation links -->
<?php
// Check to see if we need to have previous button
if ($offset >= SEARCH_ROWS) {
	$prevoffset=$offset-SEARCH_ROWS;
	echo "<A HREF='product_list.php?category_id=$category_id&keyword=$keyword&offset=$prevoffset'>
		Page pr&eacute;c&eacute;dente - </A>&nbsp;";
}

// Get total pages
$num_pages = intval($num_rows / SEARCH_ROWS);
if ($num_rows % SEARCH_ROWS) {
	$num_pages++;
}
if ($num_pages != 1)
	for ($i=1;$i<=$num_pages;$i++) {
		if (($offset < $i*SEARCH_ROWS) && ($offset >= ($i-1)*SEARCH_ROWS)) {
		$pagenumber = "$i";
	}
	else
		$pagenumber = $i;

	$newoffset = SEARCH_ROWS * ($i-1);
	echo "<A HREF=";
	$sess->purl(URL . "?page=product_list&offset=$newoffset&category_id=$category_id&keyword=$keyword");
	echo ">$pagenumber</A>&nbsp;";
}

if (($offset+SEARCH_ROWS < $num_rows) && $num_pages != 1) {
	$newoffset = $offset + SEARCH_ROWS;
	echo " - <A HREF='product_list.php?offset=$newoffset&category_id=$category_id&keyword=$keyword'
		>Page suivante</A>\n";
}
?>
</div>

<?php
include_once("footer.php");
?>
