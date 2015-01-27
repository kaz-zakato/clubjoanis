<?php
require_once("header.php");

if($HTTP_HOST=="localhost") {
	$base_dir = "C:\Documents and Settings\d\Mes documents\Web\ecom\\";
}else{
	$base_dir = "../../inc/";
}

// get functions files
require($base_dir."global_vars.php");
require($base_dir."db_mysql.php");

// Instantiate classes
$db 		= new DB;

// include global configuration
include($base_dir."lang_fr.php");
?>

<form name="quiklist" method="get" action="javascript:Update('all')">

<table width="100%" align="center" cellpadding=0 cellspacing=0 bgcolor="#FFFFFF">
<!--- <tr>
<td align="right">
<br>
<input type="submit" name="Mail.x" value="  OK  " class="Bsbttn"> 
&nbsp;<input type="button" value="Annuler" onClick="window.close();" class="Bsbttn">
</td>
</tr>
<tr><td>&nbsp;</td></tr>
</table>
<table border="0" cellpadding=0 cellspacing=0 bgcolor="#FFFFFF" id="quicklist" class="Mtable">	
<tr style="padding:3px;" bgcolor="#336699">
<th align='left' nowrap>
<font class="sw" color="#ffffff"> 
À : 
</font>
</th>
<th align='left' nowrap>
<font class="sw" color="#ffffff"> 
Cc : 
</font>
</th>
<th align='left' nowrap>
<font class="sw" color="#ffffff"> 
Cci : 
</font>
</th>
<th>&nbsp;</th>
<th align="left"><font class="sw" color="#ffffff">Surnom</font></th>
<th align="left"><font class="sw" color="#ffffff">Adresses</font></th>
</tr> --->

<?php
$xref = "
	SELECT product_id, product_name, product_company, product_vintage 
	FROM product
	WHERE product_status=2 
	ORDER BY product_company,product_name";
	
$db->query($xref);
while($db->next_record()){
	?>
	<tr>
	<td align="center" nowrap>
	<input type="checkbox" class=normal name="product:<?php echo $db->f("product_id"); ?>" value="<?php echo $db->f("product_id"); ?>" onclick="CCA(this)">
	</td>
	<td width="15">&nbsp;</td>
	<td style="padding-right:2px;"><?php echo $db->f(2); ?>
	<td width="15">&nbsp;</td>
	<td style="padding-right:2px;">
	<?php echo $db->f(1) . " " . $db->f(3);?></td>
	</tr>
	<tr class="H">
	<td colspan=5></td>
	</tr>
	<?php
}
?>
</table>

<table width="100%" align="center" cellpadding=0 cellspacing=0 bgcolor="#FFFFFF">
<tr>
<td align="right">
<br>
<input type="submit" name="Mail.x" value="  Enregistrer  "> 
&nbsp;<input type="button" value="Annuler" onClick="window.close();">
<br><br>			
</td>
</tr>
</table>
<input type="hidden" name="remainingtostr" value="">
<input type="hidden" name="smsg" value="">
<input type="hidden" name="msg" value="">
<input type="hidden" name="start" value="">
<input type="hidden" name="len" value="">
<input type="hidden" name="type" value="">
<input type="hidden" name="cust" value="1">
<input type="hidden" name="fromcompose" value="1">
<input type="hidden" name="curmbox" value="F000000001">
</form>
<script language="JavaScript">
<!--
ie = document.all?1:0
var frm = document.quiklist;

function Update(where){
	var e1, tmpStr;
	e1 =  document.quiklist.remainingtostr.value;

	for (var i = 0; i < document.quiklist.elements.length; i++){
		var e = document.quiklist.elements[i];
		if (e.checked && (e1.indexOf(e.value) == -1)){
			if (e1) e1 += ",";
			e1 += e.value;
		}
	}
	
	//window.opener.document.homepageForm.to.value
	if ((where == 'to') || (where == 'all')) {
		window.opener.document.homepageForm.products.value = e1;
	}

	window.close();
}
	
function CCA(CB){
	if(CB.checked) {
		hL(CB);
	}else{
		if(ie){
			if (!CB.parentElement.parentElement.children[0].children[0].checked)
				dL(CB);
		}else{
			Data = CB.name.explode(/:/);
			if (!frm['tO:'+Data[1]].checked)
				dL(CB);
		}
	}
}

function hL(E){
	if (ie){
		while (E.tagName!="TR"){
			E=E.parentElement;
		}
	}else{
		while (E.tagName!="TR"){
			E=E.parentNode;}
		}
	E.className = "H";
}

function dL(E){
	if (ie){
		while (E.tagName!="TR"){
			E=E.parentElement;
		}
	}else{
		while (E.tagName!="TR"){
			E=E.parentNode;
		}
	}
	E.className = "";
}
//-->
</script>

<?php
require_once("footer.php");
?>
