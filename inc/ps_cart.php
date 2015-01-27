<?php
/*
* CLASS DESCRIPTION : ps_cart
*
* The cart class is used to store products and carry them through the user's
* session in the store.
* propeties:  
* 	item() - an array of items
*       idx - the current count of items in the cart
*       error - the error message returned by validation if any
* methods:
*       display()
*				check_quantity()
*				add()
*       update()
*       delete()
*				reset()
*       get_shipping()
*************************************************************************/

class ps_cart {
	var $classname = "ps_cart";
	
	/**************************************************************************
	** name: display()
	** description: print cart
	** parameters: formated string htmlContent
	** returns: boolean
	***************************************************************************/

	function display($htmlContent){
		$cart = $_SESSION["cart"];
		if(sizeof($cart)==0){
      return false;
		}else{
			for ($i=0;$i<sizeof($cart);$i++) {
				printf($htmlContent, $cart[$i]["product_id"], $cart[$i]["label"], $cart[$i]["quantity"]);
			}
    	return true;
		}
	}
	
	/**************************************************************************
	** name: check_quantity()
	** description: check product quantity
	** parameters: int product_id, int quantity
	** returns: int quantity
	***************************************************************************/ 
	
	function check_quantity($product_id,$quantity){
		global $db;
		
		$q = "SELECT price_min_qty, price_max_qty
			FROM product_price where product_id=$product_id";
		$db->query($q);
		$db->next_record();
		$price_max_qty = $db->f("price_max_qty");
		$price_min_qty = $db->f("price_min_qty");
		if ($price_max_qty>0 && $quantity > $price_max_qty) {
			$d["error"] = "Vous ne pouvez pas commander plus de $price_max_qty bouteilles de cet article.";
			$quantity = $price_max_qty;
		}
		
		if (CHECK_STOCK) {
			$q = "SELECT product_stock ";
			$q .= "FROM product where product_id=";
			$q .= $product_id;
			$db->query($q);
			$db->next_record();
			$product_in_stock = $db->f("product_stock");
			if ($quantity > $product_in_stock) {
				$d["error"] = "Quantity selected exceeds available stock.<BR>";
				$d["error"] .= "Currently have $product_in_stock items available.";
				$quantity = $product_in_stock;
			}
		}
		
		// If no quantity sent them assume $price_min_qty
		if ($quantity == "" || !ereg("^[0-9]*$", $quantity) || $quantity < 0 || $quantity<$price_min_qty){
			$quantity = $price_min_qty;
		}

		return $quantity;
	}
	
	/**************************************************************************
	** name: add()
	** description: adds an item to the shopping cart
	** parameters: $vars ["product_id"], ["quantity"], ["label"]
	** returns: boolean
	***************************************************************************/  
	
	function add(&$d) {
		$cart 				= $_SESSION["cart"];
		$quantity 		= $this->check_quantity($d["product_id"],$d["quantity"]);
		$updated 			= false;
		
		// Check for duplicate
		for ($idx=0; $idx<sizeof($cart); $idx++) {
			if ($cart[$idx]["product_id"] == $d["product_id"]){
				// update;
				$cart[$idx]["quantity"] = $quantity;
				$updated = true;
				break;
			}
		}
		
		if (!$updated) {
		  // add
		  $idx = sizeof($cart);
			$cart[$idx]["quantity"] 	= $quantity;
			$cart[$idx]["product_id"] = $d["product_id"];
			$cart[$idx]["label"] 			= $d["label"];
			$cart[$idx]["company"] 		= $d["company"];
			$cart[$idx]["region"] 		= $d["region"];
		}
		
		$_SESSION["cart"][$idx] = $cart[$idx];
		return True; 
	}
	
	/**************************************************************************
	** name: update()
	** description: updates the quantity of a product_id in the cart
	** parameters: array vars ["product_id"] & ["quantity"]
	** returns: boolean
	***************************************************************************/    
	
	function update(&$d) {
		$cart 				= $_SESSION["cart"];
		if (!$d["product_id"]) return False;
		
		$quantity = $this->check_quantity($d["product_id"],$d["quantity"]);
		
		// update :
		if ($d["quantity"] == 0) {
			$this->delete($d);
		}else {
			for ($i=0;$i<sizeof($cart);$i++) {
				if ($cart[$i]["product_id"] == $d["product_id"]) {
					$cart[$i]["quantity"] = $quantity;
				}
			}
		}
		$_SESSION["cart"] = $cart;
		return True;
	}
	
	/**************************************************************************
	** name: delete()
	** description: deletes a given product_id from the cart
	** parameters: int product_id
	** returns: boolean
	***************************************************************************/    
	
	function delete($d) {
		
		$db = new DB;
		$cart = $_SESSION["cart"];
		
		$temp 		= array();
		$product_id = $d["product_id"];
		if (!$product_id) {
			return False;
		}
		$j = 0;
		for ($i=0;$i<sizeof($cart);$i++) {
			if ($cart[$i]["product_id"] != $product_id) {
				$temp[$j++] = $cart[$i];
			}
		}
		$cart = $temp;
		$_SESSION["cart"] = $cart;
		return True;
	}
	
	/**************************************************************************
	** name: reset()
	** description: resets the cart
	** parameters:
	** returns: boolean
	***************************************************************************/
	 
	function reset() {
		unset($_SESSION["cart"]);
		$_SESSION["cart"] = array();
		return True;
	}
	
	/**************************************************************************
	** name: get_discount()
	** description: calculates order discount
	** parameters: int total_qty
	** returns: double
	***************************************************************************/

	function get_discount($total_qty){
		if($total_qty>35){
			$order_discount = 3;
		}else{
			$order_discount = 0;
		}
		$_SESSION["order_discount"] = $order_discount;
		return $order_discount;
	}
	
	/**************************************************************************
	** name: get_shipping()
	** description: calculates order shipping
	** parameters: int total_qty
	** returns: double
	***************************************************************************/
	
	function get_shipping($order_total){
		if($total_qty<260){
			$order_shipping = 25;
		}else{
			$order_shipping = 0;
		}
		$_SESSION["order_shipping"] = $order_shipping;
		return $order_shipping;
	}
}
?>
