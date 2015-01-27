<?php
require_once("header.php");

/*****************************************************************************
* FIELDS IN TABLE
*****************************************************************************/
$selectFields = array(
	"product_company_id",
	"product_company_nom",
	"product_company_ordre",
	"product_flag",
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
		$SQL = "UPDATE product_company SET ".makeSQL($insertFields, "update")." WHERE product_company_id=".$_POST["product_company_id"];
 		if(!$db->query($SQL)) print "erreur SQL";
		$product_company_id = $_POST["product_company_id"];

	/*****************************************************************************
	* INSERT
	*****************************************************************************/
	}else if($action=="insert"){
		$SQL = "INSERT INTO product_company ".makeSQL($insertFields, "insert");
		if(!$db->query($SQL)) print "erreur SQL";
		$product_company_id = $db->inserted_key();

	/*****************************************************************************
	* DELETE
	*****************************************************************************/
	}else if($action=="delete"){
		$SQL = "DELETE FROM product_company WHERE product_company_id=".$_POST["product_company_id"];
		if(!$db->query($SQL)) print "erreur SQL"; else print "<font color=\"#FF0000\"><strong>Suppression du domaine effectuée !</strong></font>";
	}
}

/*****************************************************************************
* READ SELECTED RECORD
*****************************************************************************/
$product_company_id = (isset($_GET["product_company_id"])) ? $_GET["product_company_id"] : $_POST["product_company_id"];

if($product_company_id){
	$action 	= "update";

	$req 		= "
		SELECT ".makeSQL($selectFields)."
		FROM product_company
		WHERE product_company_id=".$product_company_id;

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
<input type='hidden' name='product_company_id' value='<?php echo $product_company_id; ?>'>
<input type='hidden' name='table' value='product_company'>
<input type='hidden' name='product_company_ordre' value='0'>
<input type='hidden' name='product_flag' value='1'>

<table width="100%">

  <tr>
    <td width="30%">
      Nom domaine
    </td>
    <td></td>
    <td>
      <input type="text" maxlength="100" name='product_company_nom' value="<?php echo $product_company_nom; ?>" size="60">
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
