<?php
/*****************************************************************************
* FIELDS IN TABLE OLD
*****************************************************************************/
/*$selectFields = array(
	"product_status",
	"product_name",
	"product_code",
	"product_full_img",
	"product_company",
	"product_vintage",
	"product_region",
	"product_stock",
	"product_vol",
	"product_desc",
	"product_sdesc",
	"product_sdesc2",
	"product_type",
	"product_type2",
	//"product_situation",
	"product_medals",
	"product_grapes",
	"product_render",
	"product_degree",
	"product_link"
	);
$dir = "images/btles/";

$SQL = "SELECT " . makeSQL($selectFields) . " FROM product
	WHERE product_id=" . $vars["product"] ."
	AND product_status>0
	AND product_status<=" . $auth["perms"];
$db->query($SQL);
$p = $db->next_array();
foreach($selectFields as $val){
	$$val = $p[$val];
}*/

/*****************************************************************************
* FIELDS IN TABLE NEW
*****************************************************************************/
$selectFields = array(
	"product_status",
	"product_name",
	"product_code",
	"product_full_img",
	"product_company",
	"product_vintage",
	"product_region",
	"product_stock",
	"product_vol",
	"product_desc",
	"product_sdesc",
	"product_sdesc2",
	"product_type",
	//"product_type2",
	//"product_situation",
	"product_medals",
	"product_grapes",
	"product_render",
	"product_degree",
	"product_link",
	"product_region_nom",
	"product_region_id",
	"product_company_id",
	"product_company_nom",
	"product_ordre"
	);
	
	
$dir = "images/btles/";

	$SQL = "SELECT " . makeSQL($selectFields) . " FROM product, product_region , product_company
		WHERE product_id=" . $vars["product"] ."
		AND product.product_company = product_company.product_company_id 
		AND product.product_region = product_region.product_region_id
		AND product_status>0
		AND product_status<=" . $auth["perms"];

$db->query($SQL);
$p = $db->next_array();
foreach($selectFields as $val){
	$$val = $p[$val];
}
?>
