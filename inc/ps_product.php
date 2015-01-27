<?php
/************************************************************************
* The ps_product class
*
* methods :
*       get_price()
*       get_order_price()
*       select_list()
*       show_snapshot()
*************************************************************************/

class ps_product {
	var $classname = "ps_product";

	/**************************************************************************
	** name: get_price()
	** description: gets price for a given product Id based on
	**              the shopper group a user belongs to and whether
	**              and item has a price or it must grab it from the
	**              parent.
	** parameters: int product_id
	** returns: array price_info
	***************************************************************************/
	function get_price($product_id) {

		$db = new DB;
		global $auth;

		$q = "SELECT price, price_currency, price_min_qty, price_max_qty, promo_price from product_price WHERE product_id='$product_id'";
		$db->query($q);
		if ($db->next_record()) {
			$price_info["product_price"]	= $db->f("price");
			$price_info["product_promo"]	= $db->f("promo_price");
			$price_info["product_currency"]	= $db->f("price_currency");
			$price_info["product_min_qty"]	= $db->f("price_min_qty");
			$price_info["product_max_qty"]	= $db->f("price_max_qty");
			$price_info["item"] = True;
			return $price_info;
		}
	}

	/**************************************************************************
	** name: get_order_price()
	** created by:
	** description: gets price for a given product Id based on
	**              the shopper group a user belongs to and whether
	**              and item has a price or it must grab it from the
	**              parent.
	** parameters: int product_id
	** returns: array price_info
	***************************************************************************/
	function get_order_price($product_id,$order_id) {

		global $db, $auth;

		$q = "SELECT items_price, items_currency, items_discount from order_items WHERE product_id=$product_id and order_id=$order_id";
		$db->query($q);
		if ($db->next_record()) {
			$price_info["product_price"]=$db->f("items_price");
			$price_info["product_currency"]=$db->f("items_currency");
			$price_info["product_discount"]=$db->f("items_discount");
			return $price_info;
		}
	}

	/**************************************************************************
	** name: select_list()
	** parameters: int product_id, int cart_idx, int min_qty
	** returns: string html select list
	***************************************************************************/
	function select_list($product_id, $cart_idx, $min_qty, $max_qty=0) {

		$cart = $_SESSION["cart"];

		if($min_qty){
			$i = $min_qty;
			$max = ($max_qty) ? $max_qty+1 : 121;
			print "<select name='quantity' onChange='document.form" . $product_id . ".submit()'>\n<option value='$i'>Qté";

				while($i<$max){
					print "<option value='$i'";
					if ($cart_idx >= 0 && $cart[$cart_idx]["quantity"]==$i)
						print " selected ";
					print ">$i";
					$i = $i + $min_qty;
				}
			print "</select>";
		}
	}

	/**************************************************************************
	** name: print_category_list()
	** description: print category list
	** parameters: int output = (0 for array, 1 for print), int category_id, string url
	** returns: print category list
	** LISTE D'AFFICHAGE DES CATEGORIES D'APPELLATION , DES DOMAINES ET DES OBJETS
	***************************************************************************/
	function print_category_list($output, $category_id, $product_list, $type){
		if($type == "1") // Si on veut afficher les appellations
		{
			echo "<font color=\"#FF9C31\"><strong>&nbsp;&nbsp;&nbsp;&nbsp;Les Appellations</strong></font><br>";
			global $db, $auth;
	
			$SQL = "SELECT r.product_region_id , r.product_region_nom 
				FROM product_region r
				ORDER BY r.product_region_ordre ASC";
			$db->query($SQL);
	
			while($db->next_record()){
			$region = str_replace("AOC ", "", $db->f("product_region_nom"));
			//$menu[$region] = $db->f("product_region_nom");
			$menu[$region] = $db->f("product_region_id");
			}
			//ksort($menu);
	
			foreach($menu as $cat_name => $cat_id){
			if($cat_name != "Aucun")
			{
					if($cat_name=="") continue;
					if(stripslashes($_GET["category_id"])==$cat_id) $thisCat = "<img src='images/fleche_menu.gif' width=9 height=10 border=0 align=\"absmiddle`\" alt=\"\">";
					else $thisCat = "<img src=\"images/blank.gif\" width=\"9\" height=\"10\" border=\"0\" align=\"absmiddle\" alt=\"\">";
		
					if($output=="menu"){
						print $thisCat . " <a href='product_list.php?category_id=" . urlencode($cat_id) ."' class='";
						if($cat_id==$_GET["category_id"]) echo "menuSelected"; else echo "menu";
						if($cat_id != 5) print "'>" . $cat_name . "</a><br>\n";
						else print "'>Champagne</a><br>\n";
					}else if($output=="select"){
						print "<option value='" . $cat_id . "'>" . $cat_name . "\n";
					}	
			 }		
			}
		}
		///////////////////////////////////////////////////
		if($type == "2") // Si on veut afficher les domaines
		{
			echo "<font color=\"#FF9C31\"><strong>&nbsp;&nbsp;&nbsp;&nbsp;Les Domaines</strong></font><br>";
			global $db, $auth;
	
			$SQL = "SELECT c.product_company_id , c.product_company_nom, c.product_flag
					FROM product_company c
					WHERE  c.product_flag = 1
					ORDER BY c.product_company_ordre ASC";
			$db->query($SQL);
	
			while($db->next_record()){
			$company = str_replace("du Pape", "", $db->f("product_company_nom"));
			//$menu[$company] = $db->f("product_company_nom");
			$menu[$company] = $db->f("product_company_id");
			}
			//ksort($menu);
	
			foreach($menu as $cat_name => $cat_id){
					if($cat_name=="") continue;
					if(stripslashes($_GET["dom"])==$cat_id) $thisCat = "<img src='images/fleche_menu.gif' width=9 height=10 border=0 align=\"absmiddle`\" alt=\"\">";
					else $thisCat = "<img src=\"images/blank.gif\" width=\"9\" height=\"10\" border=\"0\" align=\"absmiddle\" alt=\"\">";
		
					if($output=="menu"){
						print $thisCat . " <a href='product_list.php?dom=" . urlencode($cat_id) ."' class='";
						if($cat_id==$_GET["dom"]) echo "menuSelected"; else echo "menu";
						if($cat_id != 3) print "'>" . $cat_name . "</a><br>\n";
						else print "'>Champagne Louis B.</a><br>\n";
					}else if($output=="select"){
						print "<option value='" . $cat_id . "'>". $cat_name ."\n";
					}
			}
		}
		///////////////////////////////////////////////////
		if($type == "3") // Si on veut afficher les objets
		{
			echo "<font color=\"#FF9C31\"><strong>&nbsp;&nbsp;&nbsp;&nbsp;Les Objets</strong></font><br>";
			global $db, $auth;
	
			$SQL = "SELECT c.product_company_id , c.product_company_nom, c.product_flag
					FROM product_company c
					WHERE  c.product_flag = 2
					ORDER BY c.product_company_ordre ASC";
			$db->query($SQL);
	
			while($db->next_record()){
			$company = str_replace("AOC ", "", $db->f("product_company_nom"));
			//$menu[$region] = $db->f("product_region_nom");
			$menu[$company] = $db->f("product_company_id");
			}
			//ksort($menu);
	
			foreach($menu as $cat_name => $cat_id){

					if($cat_name=="") continue;
					if(stripslashes($_GET["range"])==$cat_id) $thisCat = "<img src='images/fleche_menu.gif' width=9 height=10 border=0 align=\"absmiddle`\" alt=\"\">";
					else $thisCat = "<img src=\"images/blank.gif\" width=\"9\" height=\"10\" border=\"0\" align=\"absmiddle\" alt=\"\">";
		
					if($output=="menu"){
						print $thisCat . " <a href='product_list.php?range=" . urlencode($cat_id) ."' class='";
						if($cat_id==$_GET["range"]) echo "menuSelected"; else echo "menu";
						print "'>" . $cat_name . "</a><br>\n";
					}else if($output=="select"){
						print "<option value='" . $cat_id . "'>" . $cat_name . "\n";
					}
					
			}
		}



		//if($output=="menu") print "<tr align='left'><td colspan='20' ".$bgcolor.">".$bullet."</td></tr>";
		//return $list;
 	}

	/**************************************************************************
	** name: uploadImg()
	** description:
	** parameters:
	** returns:
	***************************************************************************/
	function uploadImg($image_name, $id, $table, $key, $dir=""){
		global $db;

		if(isset($_FILES[$image_name])){
			$maxsize		= 500000;
			$filesize		= $_FILES[$image_name]['size'];
			$filetmpname	= $_FILES[$image_name]['tmp_name'];
			$filename		= enlever($_FILES[$image_name]['name']);
			$filetype		= $_FILES[$image_name]['type'];
			$new_image		= false;
			$dir			= "../images/btles/";
			
			if ($filesize>0 && $filesize < $maxsize) {
				//chmod($_FILES[$image_name]['tmp_name'], 0766);
				//copy($_FILES[$image_name]['tmp_name'], $dir.$filename);
				if (!move_uploaded_file($filetmpname, $dir . $filename)){
					print "IMAGE : Erreur d'écriture sur le disque. ";
				}else{
				  chmod ($dir . $filename, 0764);
				  $SQL = "UPDATE $table SET $image_name='$filename' WHERE $key=$id";
 					if(!$db->query($SQL)) print "IMAGE : Erreur d'écriture dans la base de données. ";
					else $new_image = $filename;
					
					/*$size = getimagesize($dir . $filename);
					$widthTemp = $size[0];
					$heightTemp = $size[1];*/
					
					// miniatures
					create_thumbnail($dir . $filename, $dir . "mini/" . $filename, 300, 1);
				}
			} else {
				if ($filesize > $maxsize)
					echo "IMAGE : Impossible d'enregistrer ce fichier. La taille maximum autorisée est de $maxsize bytes. (Votre fichier: $filesize bytes). ";
				print "IMAGE : Erreur de lecture du fichier (bouteille). ";
			}
		}
		return $new_image;
	}
	
	function uploadEtiq($image_name, $id, $table, $key, $dir=""){
		global $db;

		if(isset($_FILES[$image_name])){
			$maxsize		= 500000;
			$filesize		= $_FILES[$image_name]['size'];
			$filetmpname	= $_FILES[$image_name]['tmp_name'];
			$filename		= enlever($_FILES[$image_name]['name']);
			$filetype		= $_FILES[$image_name]['type'];
			$new_etiq		= false;
			$dir			= "../images/etiqs/";
			
			if ($filesize>0 && $filesize < $maxsize) {
				//chmod($_FILES[$image_name]['tmp_name'], 0766);
				//copy($_FILES[$image_name]['tmp_name'], $dir.$filename);
				if (!move_uploaded_file($filetmpname, $dir . $filename)){
					print "IMAGE : Erreur d'écriture sur le disque. ";
				}else{
				  $SQL = "UPDATE $table SET $image_name='$filename' WHERE $key=$id";
 					if(!$db->query($SQL)) print "IMAGE : Erreur d'écriture dans la base de données. ";
					else $new_etiq = $filename;

					create_thumbnail($dir . $filename, $dir . $filename, 120, 2);
				}
			} else {
				if ($filesize > $maxsize)
					echo "IMAGE : Impossible d'enregistrer ce fichier. La taille maximum autorisée est de $maxsize bytes. (Votre fichier: $filesize bytes). ";
				print "IMAGE : Erreur de lecture du fichier (étiquette). ";
			}
		}
		return $new_etiq;
	}
}

function create_thumbnail($monImageSource, $monImageDestination, $tailleMax, $resize_type)
{
	$picture = $monImageSource;   // Chemin de l'image source
	substr(strrchr($picture, '.'), 1);
	$dimension = $tailleMax;        // Taille maximum d'un côté de l'image
	if(substr(strrchr($picture, '.'), 1)=="gif"){
		$src_img = imagecreatefromgif($picture);
		$ext = "gif";
	}
	elseif(substr(strrchr($picture, '.'), 1)=="jpg" || substr(strrchr($picture, '.'), 1)=="jpeg"){
		 $src_img = imagecreatefromjpeg($picture);
		 $ext = "jpg";
	}
	elseif(substr(strrchr($picture, '.'), 1)=="png"){
		 $src_img = imagecreatefrompng($picture);
		 $ext = "png";
	}
	else{
		echo "Format d'image non supporté. Utilisez des *.gif, des *.jpg ou des *.png";
		exit();
	}
	
	$oh = imagesy($src_img); //Hauteur originale
	$ow = imagesx($src_img); //Largeur originale
	$height = $oh;
	$width = $ow;
	
	
	if ($resize_type == 2) {
		// define width
		$new_width = $dimension;
		$new_height = floor(($dimension/$width) * $height);
	}
	elseif ($resize_type == 3) {
		// define height
		$new_width = floor(($dimension/$height) * $width);
		$new_height = $dimension;
	}
	else {
		// define width or height because of ratio
		$ratio = $width / $height;
		if ($ratio > 1) {
			$new_width = $dimension;
			$new_height = floor(($dimension/$width) * $height);
		}
		else {
			$new_width = floor(($dimension/$height) * $width);
			$new_height = $dimension;
		}
	}
	
	if(ext=="png"){
		$dst_img = imagecreatetruecolor($new_width, $new_height);
		imagealphablending($dst_img, false);
		imagesavealpha($dst_img,true);
		$transparent = imagecolorallocatealpha($dst_img, 255, 255, 255, 127);
		imagefilledrectangle($dst_img, 0, 0, $new_width, $new_height, $transparent);
		imagecopyresampled($dst_img, $im, 0, 0, 0, 0, $new_width, $new_height,
		    $width, $height);
	}
	else{
		$dst_img = imagecreatetruecolor($new_width, $new_height);	
		imagecopyresampled($dst_img, $src_img, 0,0,0,0, $new_width, $new_height, $ow, $oh);
	}
	
	
	if($ext=="jpg") imagejpeg($dst_img, $monImageDestination);
	elseif($ext=="png") {
		imagepng($dst_img, $monImageDestination);
	}
	elseif($ext=="gif") imagegif($dst_img, $monImageDestination);
	chmod ($monImageDestination, 0764);
}

/********************* Retire les accents et caractères spéciaux de la chaine "$string" **********************/
function enlever($maChaine)
{
	$caracs = array("¥" => "Y", "µ" => "u", "À" => "A", "Á" => "A",
                	"Â" => "A", "Ã" => "A", "Ä" => "A", "Å" => "A",
                	"Æ" => "AE", "Ç" => "C", "È" => "E", "É" => "E",
                	"Ê" => "E", "Ë" => "E", "Ì" => "I", "Í" => "I",
                	"Î" => "I", "Ï" => "I", "Ð" => "D", "Ñ" => "N",
               	 	"Ò" => "O", "Ó" => "O", "Ô" => "O", "Õ" => "O",
               	 	"Ö" => "O", "Ø" => "O", "Œ"=>"OE", "Ù" => "U",
					"Ú" => "U", "Û" => "U", "Ü" => "U", "Ý" => "Y",
					"ß" => "s", "à" => "a", "á" => "a", "â" => "a",
					"ã" => "a", "ä" => "a", "å" => "a", "æ" => "ae",
					"ç" => "c", "è" => "e", "é" => "e", "ê" => "e",
					"ë" => "e", "ì" => "i", "í" => "i", "î" => "i",
					"ï" => "i", "ð" => "o", "ñ" => "n", "ò" => "o",
					"ó" => "o", "ô" => "o", "õ" => "o", "ö" => "o",
					"œ" => "oe", "ø" => "o", "ù" => "u", "ú" => "u",
					"û" => "u", "ü" => "u", "ý" => "y", "ÿ" => "y",
					" " => "_", "'" => "_", "\"" => "_", ":" => "__",
					";" => "_", "," => "_", "!" => "_",
					"?" => "_", "&" => "_", "-" => "-", "*" => "_");
	
	return strtr($maChaine, $caracs);
}
?>
