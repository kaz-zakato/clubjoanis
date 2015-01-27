<?php
require_once("header.php");

/*****************************************************************************
* FIELDS IN TABLE
*****************************************************************************/
$selectFields = array(
	"titre",
	"date_crea",
	"nbr_pers",
	"temps_prepa",
	"temps_cuisson",
	"ingredients",
	"preparation",
	"recipe_img",
	"accord_vin",
	"enligne"
	);
$dir = "../images/recipes/";

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
		$SQL = "UPDATE gastronomie SET ".makeSQL($insertFields, "update")." WHERE recette_id=".$_POST["recette_id"];
 		if(!$db->query($SQL)) print "erreur SQL";
		$recette_id = $_POST["recette_id"];

	/*****************************************************************************
	* INSERT
	*****************************************************************************/
	}else if($action=="insert"){
		$SQL = "INSERT INTO gastronomie ".makeSQL($insertFields, "insert");
		if(!$db->query($SQL)) print "erreur SQL";
		$recette_id = $db->inserted_key();

	/*****************************************************************************
	* DELETE
	*****************************************************************************/
	}else if($action=="delete"){
		$SQL = "DELETE FROM gastronomie WHERE recette_id=".$_POST["recette_id"];
		if(!$db->query($SQL)) print "erreur SQL"; else print "<font color=\"#FF0000\"><strong>Suppression de la recette effectuée !</strong></font>";
	}

	/*****************************************************************************
	* file upload (image)
	*****************************************************************************/
	if($img = $ps_recipe->uploadImgRec("recipe_img", $recette_id, "gastronomie", "recette_id", $dir)){
	  $SQL = "UPDATE gastronomie SET recipe_img='$img' WHERE recette_id=$recette_id";
 		if(!$db->query($SQL)) print "erreur SQL";
	}
}

/*****************************************************************************
* READ SELECTED RECORD
*****************************************************************************/
$recette_id = (isset($_GET["recette_id"])) ? $_GET["recette_id"] : $_POST["recette_id"];

if($recette_id){
	$action 	= "update";

	$selectFields[] = "recipe_img";
	$req 		= "
		SELECT ".makeSQL($selectFields)."
		FROM gastronomie
		WHERE recette_id=".$recette_id;

	$db->query($req);
	if($db->next_record()){
	  foreach($selectFields as $fieldName){
	    $$fieldName = htmlspecialchars(stripslashes($db->f($fieldName)));
	  }
		$recipe_img 		= $db->f("recipe_img");
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
<input type='hidden' name='page' value='admin/recettes'>
<input type='hidden' name='action' value='<?php echo $action; ?>'>
<input type='hidden' name='recette_id' value='<?php echo $recette_id; ?>'>
<input type='hidden' name='table' value='gastronomie'>
<input type='hidden' name='date_crea' value='<?php echo date("Y-m-d"); ?>'>

<table width="100%">

  <tr>
    <td width="30%">
      Titre
    </td>
    <td></td>
    <td>
      <input type="text" maxlength="100" name='titre' value=
      "<?php echo $titre; ?>" size="60">
    </td>
  </tr>

  <tr>
    <td>
      Nombre de personnes
    </td>
    <td></td>
    <td>
      <input type="text" maxlength="3" name='nbr_pers' value="<?php echo $nbr_pers; ?>" size="3">
    </td>
  </tr>


  <tr>
    <td>
      Temps de préparation
    </td>
    <td></td>
    <td>
      <input type="text" maxlength="30" name='temps_prepa' value="<?php echo $temps_prepa; ?>" size="10">
    </td>
  </tr>

  <tr>
    <td>
      Temps de cuisson
    </td>
    <td></td>
    <td>
      <input type="text" maxlength="30" name='temps_cuisson' value="<?php echo $temps_cuisson; ?>" size="10">
    </td>
  </tr>

  <tr>
    <td>
      Ingrédients
    </td>
    <td></td>
    <td>
      <textarea cols="60" rows="3" name='ingredients'><?php echo $ingredients; ?></textarea>
    </td>
  </tr>

  <tr>
    <td>
      Préparation
    </td>
    <td></td>
    <td>
      <textarea cols="60" rows="6" name='preparation'><?php echo $preparation; ?></textarea>
    </td>
  </tr>


  <tr>
    <td>
      <span style="color: #FF0000;">Statut</span>
    </td>
    <td></td>
    <td>
      <select name="enligne">
        <option <?php if($enligne=='Y') echo "selected "; ?> value='Y'>En ligne
        <option <?php if($enligne=='N') echo "selected "; ?> value='N'>Hors-ligne
      </select>
    </td>
  </tr>

  <tr>
    <td>
      Accord vin
    </td>
    <td></td>
    <td>
      <select name="accord_vin">
        <option value="">N.C.
        <?php
		$queryWines = "SELECT product_id, product_name, product_vintage FROM product WHERE product_status='1' ORDER BY product_name";
		$resWines = mysql_query($queryWines);
		while($rowWines = mysql_fetch_array($resWines)){
			echo "<option value=\"{$rowWines["product_id"]}\"";
			if($rowWines["product_id"]==$accord_vin) echo " selected";
			echo " />{$rowWines["product_name"]}";
			if($rowWines["product_vintage"]) echo " ".$rowWines["product_vintage"];
		}
		?>
      </select>
    </td>
  </tr>

  <tr class="H">
    <td colspan="5"></td>
  </tr>

  <tr>
    <td valign="top">
      Photo
    </td>
    <td></td>
    <td>
      <img src="<?php echo $dir.$recipe_img; ?>"><br clear="all">Changer l'image : <input type="File" name="recipe_img" value="" size="10">
    </td>
  </tr>

  <tr>
  <td></td>
  <td></td>
  <td>
  <p><br>
	  <input type="submit" name="Mail.x" value="Enregistrer" class="Bsbttn">
		<input type="reset" value="Annuler" class="Bsbttn"> <input type="button" value="Supprimer" class="Bsbttn" onclick="document.form_rec.action.value='delete';if(confirm('Vous allez effacer une recette !\nSouhaitez-vous continuer ?'))submit();">
	</p>
	</td>
  </tr>

</table>
</form>

<?php include_once("footer.php"); ?>
