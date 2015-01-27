<?php
if(isset($_GET["get_user"]) && isset($_GET["user_id"])){
	$userid 	= $_GET["user_id"];
	// Fichiers de configuration
	session_start();
	session_unset();
	session_destroy();
	require("../inc/global_vars.php");
	require("../inc/common_vars.php");

	error_reporting(E_ALL ^ E_NOTICE);

	// Librairies
	require("../inc/db_mysql.php");
	$db = new DB;
	require("../inc/ps_login.php");
	$ps_login = new ps_login;
	$user_infos = $ps_login->userInfos($userid);
	$ps_login->checkuser($user_infos["user_email"], $user_infos["password"],
		"../account.php?profile=1");
	exit;
}

require_once("header.php");

/*******************************************************************************
* Suppression d'utilisateurs
*******************************************************************************/

if(isset($_GET["delUser"])){
  $ordersGet = "select order_id from orders where user_id=".$_GET["delUser"];
	$db->query($ordersGet);
	while($db->next_record()){
	  $del = "delete from orders where order_id=" . $db->f("order_id");
		$db->query($del);

	  $del = "delete from order_bill where order_id=" . $db->f("order_id");
		$db->query($del);

	  $del = "delete from order_ship where order_id=" . $db->f("order_id");
		$db->query($del);

	  $del = "delete from order_items where order_id=" . $db->f("order_id");
		$db->query($del);
	}

  $del = "delete from user where user_id=" . $_GET["delUser"];
	$db->query($del);
	$del = "delete from user_bill where user_id=" . $_GET["delUser"];
	$db->query($del);
	$del = "delete from user_ship where user_id=" . $_GET["delUser"];
	$db->query($del);
}
/*******************************************************************************
* Mises à jour
*******************************************************************************/

if($_GET["action"]=="update" && $_GET["user_id"]){
	$update = "UPDATE user set
	  user_email='$user_email',
	  password='$password',
	  perms='$perms',
	  discount='$discount',
	  discount_txt='$discount_txt'
	WHERE user_id=".$_GET["user_id"];
	$db->query($update);

	$update = "UPDATE user_bill set
	  bill_first_name='$bill_first_name',
	  bill_name='$bill_name'
	WHERE user_id=".$_GET["user_id"];
	$db->query($update);
}

/*******************************************************************************
* Moteur de recherche
*******************************************************************************/
print "<form name='rec' action='user.php' method='POST'>";

$admin = true;

// get users
if($rec = $vars["recUser"]){
	$where = "
		AND (
		upper(bill_name) like upper('%$rec%')
		OR upper(bill_first_name) like upper('%$rec%')
		OR upper(user_email) like upper('%$rec%')
		)";
}

$req = "
	SELECT u.user_id, b.bill_name, b.bill_first_name
	FROM user u, user_bill b
	WHERE u.user_id=b.user_id
	AND b.bill_name<>'' $where
	ORDER BY b.bill_first_name";
$db->query($req);

if($_GET["user_id"])
	print "Modifier : <select name='user_id' onChange=\"document.location='user.php?user_id='+this.value\">";
$rec = "Rechercher : <input type='text' name='recUser' size='10'>
	<input type='button'  class='Bsbttn'
		onClick='document.forms['rec'].submit();' name='Mail.x' value='Rechercher' border=0>
	</form><hr size='1' align='left' color='marroon' width='95%'><br>";

/*******************************************************************************
* Recherche dans la BDD
*******************************************************************************/

while($db->next_record()){
	$id 							= $db->f("user_id");
	$user_name 				= $db->f("bill_name");
	$user_first_name 	= $db->f("bill_first_name");
	$password 				= $db->f("password");
  $perms 						= $db->f("perms");

	if($_GET["user_id"]){
		print "<option value=$id";
		if($id==$_GET["user_id"]) print " selected";
		print ">$user_first_name $user_name\n";
	}
	else $listUsers .= "<li><a href='user.php?user_id=$id' style='color:black'>$user_first_name $user_name</a><br>";
}
if($_GET["user_id"]){
	print "</select> - $rec";
}else{
	print $rec.$listUsers;
	require_once("footer.php");
	exit;
}

// Librairies
require("../inc/ps_login.php");
$ps_login = new ps_login;
$user = $ps_login->userInfos($vars["user_id"]);

/*******************************************************************************
* Formulaire HTML
*******************************************************************************/
?>

<form name="cart" method="GET" action="user.php">
<input type='hidden' name='user_id' value='<?php echo $vars["user_id"]; ?>'>
<input type='hidden' name='action' value='update'>

	<table>
	<tr><td colspan=3>
	<span class=titre>Informations utilisateur</span><br><br>
	</td></tr>

	<tr><td>
	Nom : </td>
	<td></td>
	<td>
	<input type=text maxlength=64 name='bill_name' value="<?php echo $user["bill_name"] ?>" size=25 class=form>
	</td></tr>

	<tr><td>
	Prénom : </td>
	<td></td>
	<td>
	<input type=text maxlength=64 name='bill_first_name' value="<?php echo $user["bill_first_name"] ?>" size=25 class=form>
	</td></tr>

	<tr><td>
	Email : </td>
	<td></td>
	<td>
	<input type=text maxlength=64 name='user_email' value='<?php echo $user["user_email"] ?>' size=25 class=form>
	</td></tr>

	<tr><td>
	Mot de passe : </td>
	<td></td>
	<td>
	<input type=text class=form maxlength=64 name='password' value='<?php echo $user["password"] ?>' size=25>
	</td></tr>

	<tr><td>
	Droits : </td>
	<td></td>
	<td>
		<select name='perms'>
	    <option <?php if($user["perms"]=='1') echo "selected "; ?> value='1'>Tout public
	    <option <?php if($user["perms"]=='2') echo "selected "; ?> value='2'>Offres Spéciales
		</select>
	</td></tr>

	<tr><td>
	Remise personnelle : </td>
	<td></td>
	<td>
	<input type=text class=form maxlength=64 name='discount' value='<?php echo $user["discount"] ?>' size=5> %
	</td></tr>

	<tr><td>
	Raison : </td>
	<td></td>
	<td>
	<input type=text class=form maxlength=100 name='discount_txt' value='<?php echo $user["discount_txt"] ?>' size=25>
	</td></tr>

	<tr>
	<td></td>
	<td></td>
	<td>
	<a href="user.php?get_user=1&user_id=<?php echo $vars["user_id"] ?>" target="new">Fiche complète</a><br><br>
	</td>
	</tr>

	<tr><td>
	Dernière visite : </td>
	<td></td>
	<td><?php echo $user["user_last_login"] ?>
	</td></tr>

	<tr><td>
	Nombre de visites : </td>
	<td></td>
	<td><?php echo $user["user_nb_login"] ?>
	</td></tr>

	<tr><td>
	Nombre de commandes : </td>
	<td></td>
	<td><?php
	$cdes = "SELECT count(*) from orders where user_id=".$vars["user_id"]." and order_status<6";
	$db->query($cdes);
	$db->next_record();
	$nbCde = $db->f(0);
	echo $nbCde;
	if($nbCde>0){
	  $messConfirm = "Cet utilisateur à passé $nbCde commandes. Etes-vous sûr(e) de vouloir le supprimer ?";
	}else{
	  $messConfirm = "Etes-vous sûr(e) de vouloir supprimer cet utilisateur ?";
	}
	?>
	</td></tr>

	<tr>
	<td></td>
	<td></td>
	<td><br>
	<input type="button"  class="Bsbttn"
		onClick="document.forms['cart'].submit();" name="Mail.x" value=" Enregistrer les modifications " border=0 class="normal">
  <input type="button" value=" Supprimer " class="Bsbttn"
		onClick="if(confirm('<?php echo $messConfirm ?>')){
			document.location.href = 'user.php?delUser=<?php echo $vars["user_id"] ?>';
		}">
	</td>
	</tr>
	</table>
</form>

<?php
require_once("footer.php");
?>
