<?php
/*
 * The ps_product_attribute class
 *
 * methods :
 *        get_category_list()
 *        print_category_list()
 *        navigation_list()
*************************************************************************/

class ps_product_category {

	/**************************************************************************
	** name: get_category_list()
	** description: get categories list and updates $_SESSION
	** parameters: int level, int category_id
	** returns: boolean
	***************************************************************************/
	function get_category_list($level, $category_id){

		$db 	= new DB;
		$menu = $_SESSION["menu"];

		if($level>0){
			$req  = "
				SELECT distinct c.category_name,c.category_id
				FROM category c, product_category pc, product p
				WHERE c.category_status=1 AND
				c.category_parent='$category_id'
				AND p.product_id=pc.product_id
				AND c.category_id=pc.category_id
				AND p.product_status=2
				ORDER BY c.category_name ASC";
		}else{
			$req  = "
				SELECT category_name,category_id FROM category
				WHERE category_status=1 AND
				category_parent=0
				ORDER BY category_name ASC";
		}

		$db->query($req);
		$i = 0;
		while ($db->next_record()) {
			$menu["idx"] = $i;
			$cat_id = $db->f("category_id");
			$menu[$menu["idx"]]["id"] 	= $cat_id;
			$menu[$menu["idx"]]["name"] = $db->f("category_name");
			if($level>0){
				$menu[$menu["idx"]]["indent"] = true;
			}else{
				$menu[$menu["idx"]]["indent"] = false;
			}
			$this->get_category_list("1",$cat_id);
			$i++;
		}
		$menu["idx"]++;

		$_SESSION["menu"] = $menu;
		return True;
	}
	
	/**************************************************************************
	** name: print_category_list()
	** description: print category list
	** parameters: int output = (0 for array, 1 for print), int category_id, string url
	** returns: print category list
	***************************************************************************/
	function print_category_list($output, $category_id, $url){
		
		global $bullet, $_GET;
		global $navig_bgcolor, $vins_bgcolor;
		
		if($_SESSION["menu"]["idx"]==0){
			$this->get_category_list(0,0);
		}
		
		$menu = $_SESSION["menu"];
		
		for ($i=0;$i<$menu["idx"];$i++) {
			$cat_id 	= $menu[$i]["id"];
			$cat_name = $menu[$i]["name"];
			
			if($menu[$i]["indent"]){
				$indent = "&nbsp; - ";
			}else{
				$indent = "<img src='images/bullet.gif' width='5' height='5' hspace='3' align='absmiddle'>";
			}
			
			if($_GET["category_id"]==$cat_id) $thisCat = "<img src='images/cat.gif' align='right' width=10 height=14>";
			else $thisCat = "";
			
			if($output=="menu"){
				print "<tr align='left'>";
				print "<td ".$bgcolor." colspan=20>";
				print $thisCat.$indent . "<a href='product_list.php?category_id=" . $cat_id ."'>" . $cat_name . "</a><br>\n";
				print "</td></tr>";
			}else if($output=="select"){
				print "<option value='" . $cat_id . "'>" . $cat_name . "\n";
			}
		}
		if($output=="menu") print "<tr align='left'><td colspan='20' ".$bgcolor.">".$bullet."</td></tr>";
		//return $list;
 	}
	
	/**************************************************************************
	** name: navigation_list()
	** description: print current category and parent categories
	** parameters: category_id
	** returns: boolean
	***************************************************************************/
	function navigation_list($category_id) {
		$db = new DB;
		static $i = 0;
    $link = "";
    
		$q = "SELECT * from category WHERE category_id='$category_id'";
		$db->query($q);
		$db->next_record();
		if ($db->f("category_parent")) {
			/*$link .= "<A HREF=";
			$link .= $sess->url(URL .
			"?page=product_list&category_id=$category_id");
			$link .= ">";*/
			$link .= $db->f("category_name");
			//$link .= "</A>";
			$category_list[$i++] = " - " . $link;
			$this->navigation_list($db->f("category_parent"));
		}else{
			/*$link = "<A HREF=";
			$link .= $sess->url(URL .
			"?page=product_list&category_id=$category_id");
			$link .= ">";*/
			$link .= $db->f("category_name");
			//$link .= "</A>";
			$category_list[$i++] = $link;
		}
		while (list($key, $value) = each($category_list)) {
			echo "$value";
		}
		return True;
	}
}
?>
