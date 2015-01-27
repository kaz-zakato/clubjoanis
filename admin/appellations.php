<?php
require_once("header.php");

/*****************************************************************************
* FIELDS IN TABLE
*****************************************************************************/
$selectFields = array(
	"product_region_id",
	"product_region_nom",
	"product_region_ordre",
	);

if(isset($_POST["action"])){

	/*****************************************************************************
	* Admin table
	*****************************************************************************/
	foreach($selectFields as $fieldName){
		$insertFields[$fieldName] = $_POST[$fieldName];
	}
	
	/*****************************************************************************
	* UPDATE
	*****************************************************************************/
	if($action=="update"){
		$SQL = "UPDATE product_region SET ".makeSQL($insertFields, "update")." WHERE product_region_id=".$_POST["product_region_id"];
 		if(!$db->query($SQL)) print "erreur SQL";
		$product_region_id = $_POST["product_region_id"];

	/*****************************************************************************
	* INSERT
	*****************************************************************************/
	}else if($action=="insert"){
		$SQL = "INSERT INTO product_region ".makeSQL($insertFields, "insert");
		if(!$db->query($SQL)) print "erreur SQL";
		$product_region_id = $db->inserted_key();

	/*****************************************************************************
	* DELETE
	*****************************************************************************/
	}else if($action=="delete"){
		$SQL = "DELETE FROM product_region WHERE product_region_id=".$_POST["product_region_id"];
		if(!$db->query($SQL)) print "erreur SQL"; else print "<font color=\"#FF0000\"><strong>Suppression du domaine effectuée !</strong></font>";
	}
}

/*****************************************************************************
* READ SELECTED RECORD
*****************************************************************************/
$product_region_id = (isset($_GET["product_region_id"])) ? $_GET["product_region_id"] : $_POST["product_region_id"];

if($product_region_id){
	$action 	= "update";

	$req 		= "
		SELECT ".makeSQL($selectFields)."
		FROM product_region
		WHERE product_region_id=".$product_region_id;

	$db->query($req);
	if($db->next_record()){
	  foreach($selectFields as $fieldName){
	    $$fieldName = htmlspecialchars(stripslashes($db->f($fieldName)));
	  }
	}
	else{
		$action = "insert";
		$recette_id = "";
	}
/*****************************************************************************
* PRINT FORM
*****************************************************************************/
}else{
	$action = "insert";
}
?>
<form name="form_rec" method="post" enctype='multipart/form-data'>
<input type='hidden' name='action' value='<?php echo $action; ?>'>
<input type='hidden' name='product_region_id' value='<?php echo $product_region_id; ?>'>
<input type='hidden' name='table' value='product_region'>
<input type='hidden' name='product_region_ordre' value='0'>

<table width="100%">

  <tr>
    <td width="30%">
      Nom appellation
    </td>
    <td></td>
    <td>
      <input type="text" maxlength="100" name='product_region_nom' value="<?php echo $product_region_nom; ?>" size="60">
    </td>
  </tr>

  <tr>
  <td></td>
  <td></td>
  <td>
  <p><br>
	  <input type="submit" name="Mail.x" value="Enregistrer" class="Bsbttn">
		<input type="reset" value="Annuler" class="Bsbttn"> <input type="button" value="Supprimer" class="Bsbttn" onClick="document.form_rec.action.value='delete';if(confirm('Vous allez effacer un domaine !\nSouhaitez-vous continuer ?'))submit();">
	</p>
	</td>
  </tr>

</table>
</form>

<?php include_once("footer.php"); ?>
