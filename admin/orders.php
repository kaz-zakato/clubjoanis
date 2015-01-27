<?php
if(isset($_GET["order_id"]) && isset($_GET["user_id"])){
	$orderid 	= $_GET["order_id"];
	$userid 	= $_GET["user_id"];
	// Fichiers de configuration
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
		"checkout.php?order_id=$orderid&user_id=$userid&impr=1&dummy=");
	exit;
}

require_once("header.php");
require_once("../inc/ps_orders.php");
$ps_order = new ps_orders;
$orderBy 	= $_GET["orderBy"];
$status 	= $_GET["status"];
?>

<table width="100%" cellpadding="0" cellspacing="0" border="0">

<tr>
	<td align="left" style="color:black; font-size:10px; font-weight:bold">
	<?php if($orderBy=="order_id" || $orderBy=="") print "<img src=../images/tri.gif width=6 height=6>"; ?>
	<a href="orders.php?orderBy=order_id&status=<?php echo $status ?>">N° Commande</a>
	</td>

	<td>&nbsp;</td>

	<td align="left" style="color:black; font-size:10px; font-weight:bold;">
	<?php if($orderBy=="order_number") print "<img src=../images/tri.gif width=6 height=6>"; ?>
	<a href="orders.php?orderBy=order_number&status=<?php echo $status ?>">N° Facture</a>
	</td>

	<td>&nbsp;</td>

	<td align="left" style="color:black; font-size:10px; font-weight:bold;">
	<?php if($orderBy=="cre_date") print "<img src=../images/tri.gif width=6 height=6>"; ?>
	<a href="orders.php?orderBy=cre_date&status=<?php echo $status ?>">Date</a>
	</td>

	<td>&nbsp;</td>

	<td align="left" style="color:black; font-size:10px; font-weight:bold;">
	<?php if($orderBy=="order_bill_name") print "<img src=../images/tri.gif width=6 height=6>"; ?>
	<a href="orders.php?orderBy=order_bill_name&status=<?php echo $status ?>">Nom prénom</a>
	</td>

	<td>&nbsp;</td>

	<td align=right style="color:black; font-size:10px; font-weight:bold;">
	<?php if($orderBy=="price") print "<img src=../images/tri.gif width=6 height=6>"; ?>
	<a href="orders.php?orderBy=price&status=<?php echo $status ?>">Total TTC</a>
	</td>

	<td colspan="2">&nbsp;</td>
</tr>
<tr><td colspan=30><hr size=1 width=100% color=black></td></tr>

<?php
if($_GET["order_id"] && $_GET["update_status"]){
	$ps_order->change_status($_GET["order_id"],$_GET["update_status"],$_GET["order_number"]);
}

// get order list
$req = "
	SELECT o.order_id, o.order_status, o.order_number, o.order_type,
	DATE_FORMAT(o.cre_date,'%d/%m/%Y') as d5,
	b.order_bill_gender, b.order_bill_first_name, b.order_bill_name,
	SUM(i.items_price*i.items_qty) as price,
	o.order_shipping, o.order_discount, o.order_pdiscount, o.user_id
	FROM orders o, order_bill b, order_items i
	WHERE o.order_id=b.order_id
	AND o.order_id=i.order_id
	";

if($status<99){
	$req .= " AND (o.order_status = '$status' OR o.order_id = '".$_GET["order_id"]."') ";
}else{
	$req .= " AND o.order_status<'4'";
}

$linkColor = "white";

$req .= " GROUP BY i.order_id";
if($orderBy != "")
  $req .= " ORDER BY $orderBy desc";
else
	$req .= " ORDER BY o.order_id desc";

$db->query($req);
$i = 0;

while($db->next_record()){
	$dateCde 						= $db->f("d5");
	$order_id 					= $db->f("order_id");
	$order_bill_gender 	= $db->f("order_bill_gender");
	$order_bill_first_name 	= $db->f("order_bill_first_name");
	$order_bill_name 		= $db->f("order_bill_name");
	$status 						= $db->f("order_status");
	$type 							= $db->f("order_type");
	$order_number 			= $db->f("order_number");
	$order_discount 		= $db->f("order_discount");
	$order_pdiscount 		= $db->f("order_pdiscount");
	$order_total				= $db->f("price");
	$order_pdiscount    = $order_total*($order_pdiscount/100);
	$order_total 				= $order_total + $db->f("order_shipping") - $order_discount - $order_pdiscount;
	$order_status 			= $ps_order->get_status($status);
	$user_id            = $db->f("user_id");

	if($status==0) {
	  $style = "color:".$order_status[1]."; font-size:10px; font-weight:bold;";
	}else{
	  $style = "color:".$order_status[1]."; font-size:10px; font-weight:normal;";
	}
	?>


	<form name="orders<?php echo $i ?>" method="get" action="#<?php echo $order_id; ?>">
	<tr>

	<td align="left" style="<?php echo $style ?>">
	<a name=<?php echo $order_id; ?></a>
	<a href="orders.php?order_id=<?php echo $order_id; ?>&user_id=<?php echo $user_id; ?>&impr=1"
		target="nex" style="<?php echo $style ?>">
	<?php echo $ps_order->get_order_val($order_id) ?></a> <?php echo  ($type) ? " &nbsp;&nbsp;&nbsp;&nbsp; $type" : ""; ?>
	</td>

	<td>&nbsp;</td>

	<td align="left" style="<?php echo $style ?>">
	<?php	if($order_number>0) echo $order_number;	?>
	</td>

	<td>&nbsp;</td>

	<td align="left" style="<?php echo $style ?>">
	<?php echo $dateCde; ?>
	</td>

	<td>&nbsp;</td>

	<td align="left" style="<?php echo $style ?>">
	<?php echo $order_bill_first_name; ?> <?php echo $order_bill_name; ?>
	</td>

	<td>&nbsp;</td>

	<td align=right style="<?php echo $style ?>">
	<?php echo number_format($order_total, 2, ',', ' ') . " €"; ?>
	</td>

	<td>&nbsp;&nbsp;&nbsp;</td>

	<td width="1">
	<INPUT TYPE=HIDDEN NAME=page VALUE="admin/orders">
	<INPUT TYPE=HIDDEN NAME=status VALUE="<?php echo $status; ?>">
	<INPUT TYPE=HIDDEN NAME="order_id" VALUE=<?php echo $order_id; ?>>
	<INPUT TYPE=HIDDEN NAME=order_number VALUE="<?php echo $order_number; ?>">
	<select name="update_status" onChange='document.orders<?php echo $i ?>.submit()'
		style="font-size:9px;">
	<option value="">Statut
	<?php echo $ps_order->get_status($status, "select"); ?>
	</select>
	</td></tr>

	<tr><td colspan=30><hr size=1 width=100% color=black></td></tr>
	</form>
	<?php
	$i++;
}
?>
</table>
<script language="JavaScript">
<!--
function go(url){

	var hWnd = window.open(url,"","width=675,height=445,resizable=yes,status=no,scrollbars=yes");
	if ((document.window != null) && (!hWnd.opener))
		hWnd.opener = document.window;
}
-->
</script>

<?php
require_once("footer.php");
?>
