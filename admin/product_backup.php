<?php
require_once("header.php");

/*****************************************************************************
* FIELDS IN TABLE
*****************************************************************************/
$selectFields = array(
	"product_status",
	"product_name",
	"product_code",
	"product_company",
	"product_vintage",
	"product_region",
	"product_stock",
	"product_vol",
	"product_desc",
	"product_sdesc",
	"product_sdesc2",
	"product_link",
	"product_type",
	"product_type2",
	//"product_situation",
	"product_medals",
	"product_grapes",
	"product_render",
	"product_degree",
	"product_home",
	"product_cado",
	"product_etiq",
	"product_ordre"
	);
$dir = "../images/btles/";
$dir2 = "../images/etiqs/";

if(isset($_POST["action"])){

	/*****************************************************************************
	* Admin table
	*****************************************************************************/
	foreach($selectFields as $fieldName){
		$insertFields[$fieldName] = $_POST[$fieldName];
	}
	$price = preg_replace("/,/",".",$_POST["price"]);
	$promoPrice = preg_replace("/,/",".",$_POST["promoPrice"]);
	$price_min_qty = $_POST["price_min_qty"];
	$price_max_qty = $_POST["price_max_qty"];
	$category_id   = $_POST["category_id"];
	
	/**********************************************************************************
	* UPDATE products to put the selected one on the homepage and on the present part
	**********************************************************************************/
	if($_POST["product_home"]=="Y"){
		$SQL = "
				UPDATE product SET product_home='N'
				WHERE product_home='Y'";
			if(!$db->query($SQL)) print "erreur SQL";
	}
	if($_POST["product_cado"]=="Y"){
		$SQL = "
				UPDATE product SET product_cado='N'
				WHERE product_cado='Y'";
			if(!$db->query($SQL)) print "erreur SQL";
	}
	
	/*****************************************************************************
	* UPDATE
	*****************************************************************************/
	if($action=="update"){
		$SQL = "UPDATE product SET ".makeSQL($insertFields, "update")." WHERE product_id=".$_POST["product_id"];
 		if(!$db->query($SQL)) print "erreur SQL";
		$product_id = $_POST["product_id"];

		$SQL = "
			UPDATE product_price SET price='$price',
			promo_price='$promoPrice',
			price_min_qty='$price_min_qty',
			price_max_qty='$price_max_qty'
			WHERE product_id=".$_POST["product_id"];
		if(!$db->query($SQL)) print "erreur SQL";

	/*****************************************************************************
	* INSERT
	*****************************************************************************/
	}else if($action=="insert"){
		$SQL = "INSERT INTO product ".makeSQL($insertFields, "insert");
		if(!$db->query($SQL)) print "erreur SQL";
		$product_id = $db->inserted_key();

		$price = preg_replace("/,/",".",$price);

		$SQL = "INSERT INTO product_price (product_id, price, promo_price, price_min_qty, price_max_qty)
			VALUES ($product_id,$price,$promoPrice,$price_min_qty,$price_max_qty)";
		if(!$db->query($SQL)) print "erreur SQL";
		
		echo "<script language=\"JavaScript\" type=\"text/javascript\">
		<!--//
		self.location.href='product.php?product_id=$product_id';
		//-->
		</script>";
	}
	/*****************************************************************************
	* DELETE
	*****************************************************************************/
	else if($action=="delete"){
		$SQL = "DELETE FROM product WHERE product_id=".$_POST["product_id"];
		$sql_photo = mysql_query("SELECT product_full_img FROM product WHERE product_id=".$_POST["product_id"]);
		$photo = $dir;
		$photo .= mysql_result($sql_photo,0,"product_full_img");
		$photo2 = $dir;
		$photo2 .= "mini/";
		$photo2 .= mysql_result($sql_photo,0,"product_full_img");
		if(!$db->query($SQL)) print "erreur SQL"; 
		else 
		{
		unlink($photo);
		unlink($photo2);
		print "<font color=\"#FF0000\"><strong>Suppression du produit effectuée !</strong></font>";
		}
	}
	/*****************************************************************************
	* file upload (image)
	*****************************************************************************/
	if ($product_full_img!="none" && $product_full_img_name!=""){
		if ($product_full_img_type == "image/gif" || $product_full_img_type == "image/pjpeg" || $product_full_img_type == "image/jpeg"){
			if($img = $ps_product->uploadImg("product_full_img", $product_id, "product", "product_id", $dir)){
			  $SQL = "UPDATE product SET product_full_img='$img', product_small_img='$img' WHERE product_id=$product_id";
		 		if(!$db->query($SQL)) print "erreur SQL";
				clearstatcache();
			}
		}
	}
	/*
	if ($product_etiq!="none" && $product_etiq_name!=""){
		if ($product_etiq_type == "image/gif" || $product_etiq_type == "image/pjpeg" || $product_etiq_type == "image/jpeg"){
			if($etiq = $ps_product->uploadEtiq("product_etiq", $product_id, "product", "product_id", $dir2)){
			  $SQL = "UPDATE product SET product_etiq='$etiq' WHERE product_id=$product_id";
		 		if(!$db->query($SQL)) print "erreur SQL";
				clearstatcache();
			}
		}
	}
	*/
}

/*****************************************************************************
* READ SELECTED RECORD
*****************************************************************************/
$product_id = (isset($_GET["product_id"])) ? $_GET["product_id"] : $_POST["product_id"];

if($product_id){
	$action 	= "update";

	$selectFields[] = "product_full_img";
	$selectFields[] = "product_etiq";
	$req 		= "
		SELECT ".makeSQL($selectFields).",
		px.price, px.promo_price, px.price_min_qty, px.price_max_qty,product_region_nom,
		product_region_id,product_company_id,product_company_nom
		FROM product p, product_price px, product_company c, product_region r
		WHERE p.product_id=px.product_id AND p.product_company = c.product_company_id AND p.product_region = r.product_region_id
		AND p.product_id=".$product_id;

	$db->query($req);
	if($db->next_record()){
	  foreach($selectFields as $fieldName){
	    $$fieldName = htmlspecialchars(stripslashes($db->f($fieldName)));
	  }
		$price 					= $db->f("price");
		$promoPrice				= $db->f("promo_price");
		$price_min_qty 			= $db->f("price_min_qty");
		$price_max_qty 			= $db->f("price_max_qty");
		$product_full_img 		= $db->f("product_full_img");
		$product_region_nom		= $db->f("product_region_nom");
		$product_region_id		= $db->f("product_region_id");		
		$product_company_nom	= $db->f("product_company_nom");
		$product_company_id		= $db->f("product_company_id");	
	}
/*****************************************************************************
* PRINT FORM
*****************************************************************************/
}else{
	$action = "insert";
}
?>
<form method="post" enctype='multipart/form-data' name='product'>
<input type='hidden' name='page' value='admin/product'>
<input type='hidden' name='action' value='<?php echo $action; ?>'>
<input type='hidden' name='product_id' value='<?php echo $product_id; ?>'>
<input type='hidden' name='table' value='product'>

<table width="100%">

  <tr>
    <td width="30%">
      Nom
    </td>
    <td></td>
    <td>
      <input type="text" maxlength="100" name='product_name' value=
      "<?php echo $product_name; ?>" size="60">
    </td>
  </tr>

  <tr>
    <td>
      Millesime
    </td>
    <td></td>
    <td>
      <select name='product_vintage'>
        <option value="">N.M.</option>
				<?php
          for($i=(date("Y")-20);$i<=date("Y");$i++){
            echo "<option ";
            if($product_vintage==$i) echo " selected ";
            echo " value='$i'>$i</option>";
          }
          ?>
        </option>
      </select>
    </td>
  </tr>

  <tr>
    <td>
      Domaine
    </td>
    <td></td>
    <td>
	   <select name='product_company'>
				<?php
		/////AFFICHAGE DE LA LISTE DES DOMAINES////
		$sql = 'SELECT product_company_nom, product_company_id FROM product_company WHERE product_flag = 1';  
		$req = mysql_query($sql); 
		while($data = mysql_fetch_assoc($req)) 
			{ 
			echo "<option ";
            if($product_company_nom==$data['product_company_nom']) echo " selected ";
            echo " value = " .$data['product_company_id']. ">";
			echo $data['product_company_nom'];
			echo "</option>";
			} 
          ?>
      </select>
    </td>
  </tr>
  
  <tr>
    <td>
      Appellation
    </td>
    <td></td>
    <td>
		<select name='product_region'>
				<?php
		/////AFFICHAGE DE LA LISTE DES DOMAINES////
		$sql = 'SELECT product_region_nom, product_region_id FROM product_region';  
		$req = mysql_query($sql); 
		while($data = mysql_fetch_assoc($req)) 
			{ 
			if($data['product_region_nom'] != "Aucun")
			{
				echo "<option ";
				if($product_region_nom==$data['product_region_nom']) echo " selected ";
				echo " value = " .$data['product_region_id']. ">";
				echo $data['product_region_nom'];
				echo "</option>";
			}
			} 
          ?>
      </select>
    </td>
  </tr>

  <tr>
    <td>
      Description
    </td>
    <td></td>
    <td>
      <textarea cols="60" rows="5" name='product_desc'><?php echo $product_desc; ?></textarea>
    </td>
  </tr>


  <tr>
    <td>
      Code
    </td>
    <td></td>
    <td>
      <input type="text" maxlength="32" name='product_code' value="<?php echo $product_code; ?>" size="10">
    </td>
  </tr>


  <tr>
    <td>
      Prix TTC/bouteille
    </td>
    <td></td>
    <td>
      <input type="text" maxlength="10" name='price' value="<?php echo number_format($price, 2, ',', ' '); ?>" size=10>
    </td>
  </tr>
  <tr>
    <td>
      Prix promo TTC/bouteille
    </td>
    <td></td>
    <td>
      <input type="text" maxlength="10" name='promoPrice' value="<?php echo number_format($promoPrice, 2, ',', ' '); ?>" size=10>
    </td>
  </tr>


  <tr>
    <td>
      Minimum
    </td>
    <td></td>
    <td>
      <select name='price_min_qty'>
        <option <?php if($price_min_qty=='120') echo "selected "; ?> value='120'>120 btles</option>
        <option <?php if($price_min_qty=='64') echo "selected "; ?> value='64'>64 btles</option>
        <option <?php if($price_min_qty=='48') echo "selected "; ?> value='48'>48 btles</option>
        <option <?php if($price_min_qty=='24') echo "selected "; ?> value='24'>24 btles</option>
        <option <?php if($price_min_qty=='12') echo "selected" ?> value='12'>12 btles</option>
        <option <?php if($price_min_qty=='6'|| $price_min_qty=='') echo "selected "; ?> value='6'>6 btles</option>
        <option <?php if($price_min_qty=='3') echo "selected "; ?> value='3'>3 btles</option>
        <option <?php if($price_min_qty=='1') echo "selected "; ?> value='1'>1 btle</option>
      </select>
    </td>
  </tr>

  <tr>
    <td>
      Maximum
    </td>
    <td></td>
    <td>
      <select name='price_max_qty'>
        <option <?php if($price_max_qty=='0' || $price_max_qty=='') echo "selected "; ?> value='0'>Aucun</option>
        <option <?php if($price_max_qty=='120') echo "selected "; ?> value='120'>120 btles</option>
        <option <?php if($price_max_qty=='64') echo "selected "; ?> value='64'>64 btles</option>
        <option <?php if($price_max_qty=='48') echo "selected "; ?> value='48'>48 btles</option>
        <option <?php if($price_max_qty=='24') echo "selected "; ?> value='24'>24 btles</option>
        <option <?php if($price_max_qty=='12') echo "selected "; ?> value='12'>12 btles</option>
        <option <?php if($price_max_qty=='6') echo "selected "; ?> value='6'>6 btles</option>
        <option <?php if($price_max_qty=='3') echo "selected "; ?> value='3'>3 btles</option>
        <option <?php if($price_max_qty=='1') echo "selected "; ?> value='1'>1 btle</option>
      </select>
    </td>
  </tr>


  <tr>
    <td>
      Vol/bouteille
    </td>
    <td></td>
    <td>
      <select name="product_vol">
        <option <?php if($product_vol=='75') echo "selected "; ?> value='75'>75 cl</option>
        <option <?php if($product_vol=='37.5') echo "selected "; ?> value='37.5'>37.5 cl</option>
        <option <?php if($product_vol=='150') echo "selected "; ?> value='150'>150 cl</option>
        <option <?php if($product_vol=='300') echo "selected "; ?> value='300'>300 cl</option>
        <option <?php if($product_vol=='70') echo "selected "; ?> value='70'>70 cl</option>
        <option <?php if($product_vol=='50') echo "selected "; ?> value='50'>50 cl</option>
      </select>
    </td>
  </tr>


  <tr>
    <td>
      <span style="color: #FF0000;">Statut</span>
    </td>
    <td></td>
    <td>
      <select name="product_status">
        <option <?php if($product_status=='0') echo "selected "; ?> value='0'>Hors-ligne</option>
        <option <?php if($product_status=='1') echo "selected "; ?> value='1'>En ligne</option>
        <!--<option <?php if($product_status=='2') echo "selected "; ?> value='2'>Offres Spéciales-->
      </select>
    </td>
  </tr>
  <tr>
    <td>
      <span style="color: #FF0000;">En page d'accueil</span>
    </td>
    <td></td>
    <td>
      <select name="product_home">
        <option <?php if($product_home=='N') echo "selected "; ?> value='N'>Non</option>
        <option <?php if($product_home=='Y') echo "selected "; ?> value='Y'>Oui</option>
      </select>
    </td>
  </tr>
  <tr>
    <td>
      <span style="color: #FF0000;">Idée cadeau</span>
    </td>
    <td></td>
    <td>
      <select name="product_cado">
        <option <?php if($product_cado=='N') echo "selected "; ?> value='N'>Non</option>
        <option <?php if($product_cado=='Y') echo "selected "; ?> value='Y'>Oui</option>
      </select>
    </td>
  </tr>


  <tr>
    <td>
      Pays
    </td>
    <td></td>
    <td>
      <input type="text" maxlength="64" name='product_country' value="<?php if($product_country){ echo $product_country; }else{ echo 'France'; } ?>"size="60">
    </td>
  </tr>

  <tr>
    <td>
      Lien (revue de presse, etc)
    </td>
    <td></td>
    <td>
      <input type="text" maxlength="100" name='product_link' value="<?php echo $product_link; ?>"size="60">
    </td>
  </tr>

  <tr>
    <td valign="top">
      Photos
    </td>
    <td></td>
    <td>
      <img src="<?php echo $dir."mini/".$product_full_img; ?>" align="left" style="margin-right: 10px;"><img src="<?php echo $dir.$product_full_img; ?>"><br clear="all">Changer l'image : <input type="File" name="product_full_img"><br><br>
    </td>
  </tr>
  <!--
  <tr>
    <td valign="top">
      Etiquette
    </td>
    <td></td>
    <td>
      <img src="<?php echo $dir2.$product_etiq; ?>"><br clear="all">Changer l'étiquette : <input type="File" name="product_etiq">
    </td>
  </tr>
  
  
  <tr>
    <td>
      Situation
    </td>
    <td></td>
    <td>
      <textarea cols="60" rows="2" name='product_situation'><?php echo $product_situation; ?></textarea>
    </td>
  </tr>
-->
  <tr>
    <td>
      Récompenses
    </td>
    <td></td>
    <td>
      <textarea cols="60" rows="5" name='product_medals'><?php echo $product_medals; ?></textarea>
    </td>
  </tr>

  <tr>
    <td>
      Cépages
    </td>
    <td></td>
    <td>
      <input type="text" maxlength="150" name='product_grapes' value="<?php echo $product_grapes; ?>" size="60">
    </td>
  </tr>

  <tr>
    <td>
      Vinification
    </td>
    <td></td>
    <td>
      <textarea cols="60" rows="3" name='product_sdesc'><?php echo $product_sdesc; ?></textarea>
    </td>
  </tr>

  <tr class="H">
    <td colspan="5"></td>
  </tr>

  <tr>
    <td>
      Rendement
    </td>
    <td></td>
    <td>
      <input type="text" maxlength="50" name='$product_render' value="<?php echo $product_render; ?>" size="60">
    </td>
  </tr>

  <tr>
    <td>
      Critiques
    </td>
    <td></td>
    <td>
      <textarea cols="60" rows="5" name='product_sdesc2'><?php echo $product_sdesc2; ?></textarea>
    </td>
  </tr>

  <tr>
    <td>
      Degrés
    </td>
    <td></td>
    <td>
      <input type="text" maxlength="255" name='product_degree' value="<?php echo $product_degree; ?>" size="60">
    </td>
  </tr>

  <tr class="H">
    <td colspan="5"></td>
  </tr>

  <tr>
    <td>
      Couleur
    </td>
    <td></td>
    <td>
      <select name="product_type">
        <option value="">N.C.</option>
        <option <?php if($product_type=='Rouge') echo " selected "; ?>value="Rouge">Rouge</option>
        <option <?php if($product_type=='Blanc') echo " selected "; ?>value="Blanc">Blanc</option>
        <option <?php if($product_type=='Rosé') echo " selected "; ?>value="Rosé">Rosé</option>
      </select>
    </td>
  </tr>

<?php /*?>  <tr>
    <td>
      Type
    </td>
    <td></td>
    <td>
      <select name="product_type2">
        <option <?php if($product_type2=='Vin') echo " selected "; ?>value="Vin">Vin
        <option <?php if($product_type2=='Champagne') echo " selected "; ?>value="Champagne">Champagne
        <option <?php if($product_type2=='Huile') echo " selected "; ?>value="Huile">Huile
        <option <?php if($product_type2=='Les délices') echo " selected "; ?>value="Coffret">Les délices
		<option <?php if($product_type2=='Les objets du vin') echo " selected "; ?>value="Objet">Les objets du vin
      </select>
    </td>
  </tr><?php */?>

  <tr>
  <td></td>
  <td></td>
  <td>
  <p><br>
  	  <input type="hidden" name="product_ordre" value ="<? if(isset($product_ordre)) echo $product_ordre; else echo "25"; ?>" />		
	  <input type="submit" name="Mail.x" value="Enregistrer" class="Bsbttn">
		<input type="reset" value="Annuler" class="Bsbttn">
		<input type="button" value="Supprimer" class="Bsbttn" onClick="document.product.action.value='delete';if(confirm('Vous allez effacer un produit !\nSouhaitez-vous continuer ?'))submit();">
	</p>
	</td>
  </tr>

</table>
</form>

<?php include_once("footer.php"); ?>
