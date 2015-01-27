<?php
require_once("header.php");

/*****************************************************************************
* FIELDS IN TABLE
*****************************************************************************/
$selectFields = array(
	"content_home",
	"content_right1",
	"content_right1_link",
	"content_right2",
	"content_right2_link",
	"content_right3",
	"content_right3_link"
	);
$dir = "../images/";
$titre_page = "Textes et liens";

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
	if($_POST["action"]=="update"){
		$SQL = "UPDATE contents SET ".makeSQL($insertFields, "update")." WHERE content_id=".$_POST["content_id"];
 		if(!$db->query($SQL)) print "erreur SQL";
		$content_id = $_POST["content_id"];

	/*****************************************************************************
	* INSERT
	*****************************************************************************/
	}else if($action=="insert"){
		$SQL = "INSERT INTO contents ".makeSQL($insertFields, "insert");
		if(!$db->query($SQL)) print "erreur SQL";
		$content_id = $db->inserted_key();
	}

	/*****************************************************************************
	* file upload (image)
	*****************************************************************************/
	$ps_product->uploadImg("content_right1_img", $content_id, "contents", "content_id", $dir);
	$ps_product->uploadImg("content_right2_img", $content_id, "contents", "content_id", $dir);
	$ps_product->uploadImg("content_right3_img", $content_id, "contents", "content_id", $dir);
}

/*****************************************************************************
* READ SELECTED RECORD
*****************************************************************************/
$content_id = (isset($_GET["content_id"])) ? $_GET["content_id"] : $_POST["content_id"];
if(!$content_id || ($content_id != 1 && $content_id != 2)) $content_id = 1;

if($content_id){
	$action 	= "update";
	
	$selectFields[] = "content_right1_img";
	$selectFields[] = "content_right2_img";
	$selectFields[] = "content_right3_img";
	
	$req 		= "
		SELECT ".makeSQL($selectFields)."
		FROM contents
		WHERE content_id=".$content_id;
	$db->query($req);
	if($db->next_record()){
	  foreach($selectFields as $fieldName){
	    $$fieldName = htmlspecialchars_decode(stripslashes($db->f($fieldName)));
      // $content_home = htmlspecialchars(stripslashes($db->$fieldName));
	  }
	}
/*****************************************************************************
* PRINT EMPTY FORM
*****************************************************************************/
}else{
	$action = "insert";
}
?>

<span class="titre">EDITO</span><br>

<form enctype='multipart/form-data' method="post">
<input type='hidden' name='page' value='admin/product'>
<input type='hidden' name='action' value='<?php echo $action; ?>'>
<input type='hidden' name='content_id' value='<?php echo $content_id; ?>'>
<input type="hidden" name="MAX_FILE_SIZE" value="30000">

<table width="100%">
  <tr>
    <td colspan="3">
      <?php if($content_id==1) echo "Page d'accueil";?><hr size=1>
    </td>
  </tr>
  
  <tr>
    <td width="30%">
      Texte
    </td>
    <td></td>
    <td>
      <textarea cols="40" rows="5" name="content_home"><?php echo $content_home; ?></textarea>
    </td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td>
	<p class="c4"><br>
		<input type="submit" name="Mail.x" value=" Enregistrer" class="Bsbttn">
	<input type="reset" value=" Annuler" class="Bsbttn">
	</p>
    </td>
  </tr>
</table>
</form>

<?php include_once("footer.php"); ?>


