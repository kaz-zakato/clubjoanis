<?php
require_once("header.php");
require_once("connec_bd.php")

//error_reporting(6);
?>

<span class="titre"><br>
<b>Administration de l'ordre des domaines</b>
<br>
<br>
</span>
<?php
//error_reporting(0);

// gestion de l'ordre des produits
If ($ValidationOrdre=="Oui")
{
$numVin=explode(",",$ChaineTitre);
	For ($i=0;$i<Count($numVin);$i++)
	{
		$CleN=$numVin[$i];
		$ordre = $i + 1;
		mysql_query("update LOW_PRIORITY product_company set product_company_ordre='$ordre' where product_company_id='$CleN'",$dbh);
	}
	echo "Mise à jour réussie.";

}
else
{


	$id=mysql_query("SELECT * FROM product_company WHERE product_flag=1 ORDER by product_company_ordre",$dbh);
	$nump=mysql_numrows($id);

?>
<script language="JavaScript">
<!--// Hide
var TblTitre = new Array (<?echo $nump?>);
var TblN = new Array (<?echo $nump?>);

<?
	For ($j=0;$j<$nump;$j++)
	{
	$N=mysql_result($id,$j,"product_company_id");
	//$vins=Stripslashes(substr(mysql_result($id,$j,"product_region_nom"),0,150));
	$appellation=Stripslashes(substr(mysql_result($id,$j,"product_company_nom"),0,150));
	//$couleur=Stripslashes(mysql_result($id,$j,"col.nom_fr"));
	//$millesimes=mysql_result($id,$j,"vin.millesime");
	//$contenance=Stripslashes(mysql_result($id,$j,"cl.nom"));
		?>			
		TblN[<?echo $j?>]=<?echo $N?>;
		TblTitre[<?Echo $j?>]="<?  echo $appellation;
									?>";
		<?
	}
?>

function Haut(NomForm,ChampListe)
{
		var selection = document.forms[NomForm].elements[ChampListe].options.selectedIndex;

	  if (selection>0)
	  {
			
			//Option sélectionné
			var ChampTexte = TblTitre[selection-1];
			var ChampN = TblN[selection-1];

			Opt = new Option(ChampTexte,ChampN);

			document.forms[NomForm].elements[ChampListe].options[selection]=Opt;
			

			//Option du dessus
			var ChampTexte = TblTitre[selection];
			var ChampN = TblN[selection];

			Opt = new Option(ChampTexte,ChampN);

			document.forms[NomForm].elements[ChampListe].options[selection-1]=Opt;

				
			// Mise à jour du tableau en js
				var TmpTitre = TblTitre[selection];
				var TmpN = TblN[selection];
				
				TblN[selection]=TblN[selection-1];
				TblTitre[selection]=TblTitre[selection-1];

				TblN[selection-1]=TmpN;
				TblTitre[selection-1]=TmpTitre;

				document.forms[NomForm].elements[ChampListe].options.selectedIndex=selection-1;
	  }
}




function Bas(NomForm,ChampListe)
{
		var selection = document.forms[NomForm].elements[ChampListe].options.selectedIndex;

	  if (selection<<?Echo $nump-1?>)
	  {
			
			//Option sélectionné
			var ChampTexte = TblTitre[selection+1];
			var ChampN = TblN[selection+1];

			Opt = new Option(ChampTexte,ChampN);

			document.forms[NomForm].elements[ChampListe].options[selection]=Opt;
			

			//Option de dessous
			var ChampTexte = TblTitre[selection];
			var ChampN = TblN[selection];

			Opt = new Option(ChampTexte,ChampN);

			document.forms[NomForm].elements[ChampListe].options[selection+1]=Opt;

				
			// Mise à jour du tableau en js
				var TmpTitre = TblTitre[selection];
				var TmpN = TblN[selection];
				
				TblN[selection]=TblN[selection+1];
				TblTitre[selection]=TblTitre[selection+1];

				TblN[selection+1]=TmpN;
				TblTitre[selection+1]=TmpTitre;

				document.forms[NomForm].elements[ChampListe].options.selectedIndex=selection+1;
	  }
}


function Valider(NomForm)
{
	var NbArticle = 0
	document.forms[NomForm].elements["ChaineTitre"].value="";
	
	while (NbArticle<<?Echo $nump?>)
	{
		if (document.forms[NomForm].elements["ChaineTitre"].value!="") document.forms[NomForm].elements["ChaineTitre"].value=document.forms[NomForm].elements["ChaineTitre"].value+',';
			document.forms[NomForm].elements["ChaineTitre"].value=document.forms[NomForm].elements["ChaineTitre"].value+TblN[NbArticle];
		NbArticle++;
	}
	//alert(document.forms[NomForm].elements["ChaineTitre"].value);
	
	document.forms[NomForm].submit();

}

// end hiding --->
</script>

<?php /*?><br><br>
<b>Sélection de la catégorie :</b>
<form Name="FormListeCat">
<table width="450" Align="center" border="0">
	<tr>
		<td Align="center">
		<select name="cat" onChange="rec(this.form.elements[0])">
		<?
		$ic=mysql_query("select id ,nom_fr from ".$tableGammes." order by ordre");
		$numd=mysql_numrows($ic);
		
		For ($d=0;$d<$numd;$d++)
		{
			$N=mysql_result($ic,$d,"id");
			$nom=Stripslashes(mysql_result($ic,$d,"nom_fr"));
		?>
			<option value="admin_catalogue.php?ordre_vins=1&id_cat=<?Echo $N?>"<?if($id_cat==$N) echo " selected";?>><?Echo $nom;?>
		<?
		}
		?>
		</select>
		</td>
	</tr>
</table>
</form><?php */?>

<br>
<form Name="FormOrdre" Action="domaines_ordre.php?list=1&ValidationOrdre=Oui" Method="Post">
		<Input Type="Hidden" Name="ChaineTitre" Value="">
		<table Align="center" border="0">
			<tr>
				<td Align="center">
					<p>
					<font face="arial" size="-2">

					<select name="listeordre" size=<?Echo $nump?>>
						<?
						For ($j=0;$j<$nump;$j++)
						{
								$N=mysql_result($id,$j,"product_company_id");
								//$vins=Stripslashes(substr(mysql_result($id,$j,"product_region_nom"),0,150));
								$appellation=Stripslashes(substr(mysql_result($id,$j,"product_company_nom"),0,150));
								//$couleur=Stripslashes(mysql_result($id,$j,"col.nom_fr"));
								//$millesimes=mysql_result($id,$j,"vin.millesime");
								//$contenance=Stripslashes(mysql_result($id,$j,"cl.nom"));
							?>
							<option value="<?Echo $N?>"><? echo $appellation;
						}
						?>
					</select> </font>
					<script language="JavaScript">
						document.forms['FormOrdre'].elements['listeordre'].options.selectedIndex=0;
					</SCRIPT>
				</td>
				<td>
					&nbsp;<A Href="javascript:Haut('FormOrdre','listeordre')"><b>Monter</b></A>
					<BR><BR>
					&nbsp;<A Href="javascript:Bas('FormOrdre','listeordre')"><b>Descendre</b></A>
				</td>
			</tr>
		</table>
		<br><br>
		<Center><A Href="javascript:Valider('FormOrdre')"><b>VALIDER</b></A></Center>
</form>
<? } ?>

<?php include_once("footer.php"); ?>
