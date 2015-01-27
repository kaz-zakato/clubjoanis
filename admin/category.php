<?php
require_once("header.php");

// choix des produits à modifier
if($listProduct){
?>
Sélectionnez une catégorie à modifier :<br><br>
<ul>
<?php
// get order list
$req = "
	SELECT *
	FROM category
	ORDER BY category_name";
$db->query($req);
echo "<ul>";

while($db->next_record()){
	$category_id = $db->f("category_id");
	$category_name = $db->f("category_name");
	$category_parent = $db->f("category_parent");
	?>
	
	<li><font color=<?php echo $linkColor; ?>>
	<a href="category.php?category_id=<?echo $category_id; ?>"><font color=<?php echo $linkColor; ?>><?php echo $category_name; ?></font></a>
	</font><?php
}
echo "</ul>";

// ajout/modification
}else{


if($action){
	if($action=="update"){
		$category_name = preg_replace("/'/","''",$category_name);
		
		$req = "UPDATE category SET category_name='$category_name', category_status='$category_status' WHERE category_id=$category_id";
		$titre_page = "Modification d'une catégorie";
		$db->query($req);
		
	}else if($action=="insert"){
		$category_name = preg_replace("/'/","''",$category_name);
		
		$req = "INSERT INTO category (cre_date, category_name, category_parent, category_status) VALUES (now(), '$category_name', '$category_parent', '$category_status')";
		$db->query($req);
		$category_id = $db->inserted_key();
		
		$titre_page = "Modification d'une catégorie";
		$action = "update";
	}
}

if($category_id){
	$action 	= "update";
	$req 		= "
		SELECT *
		FROM category
		WHERE category_id=$category_id";
	$titre_page = "Modification d'une catégorie ";
	$db->query($req);
	$db->next_record();
	
	$category_name = $db->f("category_name");
	$category_status = $db->f("category_status");
	$category_parent = $db->f("category_parent");
	
}else{
	$action = "insert";
	$titre_page = "Ajout d'une catégorie ";
}
?>

<form enctype='multipart/form-data' method=get>
<input type='hidden' name='page' value='admin/category'>
<input type='hidden' name='action' value='<?php echo $action; ?>'>
<input type='hidden' name='category_id' value='<?php echo $category_id; ?>'>

<font size=+1><?php echo $titre_page; ?>:</font>
<hr align="left" width="500" size="1" noshade>
<br>

<table width=100%>
<tr><td width=50%>
Nom de la catégorie : </td>
<td></td>
<td><input type="text" name="category_name" value="<?php echo $category_name; ?>">
</td></tr>
<tr class="H"><td colspan=3></td></tr>
<tr><td width=20%>
En ligne</td>
<td></td>
<td>
<select name=category_status>
 <option <?php if($category_status=='Oui') echo "selected "; ?>value='Oui'>Oui
 <option <?php if($category_status=='Non') echo "selected "; ?>value='Non'>Non
</select>
</td></tr>
<tr class="H"><td colspan=3></td></tr>
<tr><td>Si la catégorie est une sous-rubrique, sélectionner son parent : 
</td><td></td><td>
<?php 
print "<select name=category_parent>
	<option value='0'>";

$db1 		= new DB;
$db2 		= new DB;
$xref 		= "SELECT category_id, category_name FROM category where category_parent=0 ORDER BY category_name";
$db1->query($xref);

while($db1->next_record()){
	$selected 	= "";
	if($db1->f(0)==$category_parent && $category_parent!='0') $selected = " selected ";
	print "\n<option value=" . $db1->f(0) . $selected . ">" . $db1->f(1);
}
?>
</select>
</td></tr>
<tr class="H"><td colspan=3></td></tr>
</table>
<p align=right><input type="submit" name="Mail.x" value="  Enregistrer  " class="Bsbttn"> 
&nbsp;<input type="reset" value=" Annuler " class="Bsbttn">
</form>

<P>
<font size="-2"><b>Information :</b> les sous-rubriques n'apparaissent que si elles contiennent des produits. Les catégories de niveau supérieur apparaissent dès qu'elles sont "En Ligne".</font>
<?php
}
require_once("footer.php");
?>
