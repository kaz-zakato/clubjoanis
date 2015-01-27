<?php
/*
* CLASS DESCRIPTION : ps_orders
*
* methods:
*       add()
*				modify_user()
*       change_status()
*				order_status()
*				orderDetails()
*       get_status
*       read()
*************************************************************************/

class ps_orders {
	var $userShip;
	var $userOrderShip;
	var $userBill;
	var $userOrderBill;
	var $details;

	function ps_orders(){

		$this->userShip = array(
			"ship_gender",
			"ship_name",
			"ship_first_name",
   		"ship_company",
			"ship_address1",
			"ship_address2",
			"ship_city",
			"ship_zip",
			"ship_phone1",
			"ship_phone2",
			"ship_mobile",
			"ship_fax"
		  );
		foreach($this->userShip as $key => $val){
    	$this->userOrderShip[] = "order_".$val;
    }

    $this->userBill = array(
			"bill_gender",
			"bill_name",
			"bill_first_name",
			"bill_company",
			"bill_address1",
			"bill_address2",
			"bill_city",
			"bill_zip",
			"bill_phone1",
			"bill_phone2",
			"bill_mobile",
			"bill_fax"
			);
		foreach($this->userBill as $key => $val){
    	$this->userOrderBill[] = "order_".$val;
    }
	}

	/**************************************************************************
	** name: add()
	** description: add user and order informations
	** parameters: int user_id, array cart
	** returns: int order_id
	***************************************************************************/

	function add($user_id, &$cart, $order_id=0, $type=""){
		global $db, $ps_product, $vars, $auth;
		$order_shipping = $_SESSION["order_shipping"];
		$order_discount = $_SESSION["order_discount"];

		if($order_id==0){
		  $order_div1 		= $vars["order_div1"];
			$req = "INSERT INTO orders
				(cre_date, user_id, order_status, order_shipping, order_discount, order_div1, order_div2, order_pdiscount, order_type)
				values
				(now(), $user_id, '0', '$order_shipping', '$order_discount', '".addslashes($order_div1)."', '".addslashes($order_div2)."', '".$auth["discount"]."', '$type')";
			$db->query($req);
			$order_id = $db->inserted_key();
			$_SESSION["order_id"] = $order_id;
		}

		$req = "INSERT INTO order_bill
				(order_id, " . implode(", ", $this->userOrderBill) . ")
				SELECT
				'$order_id', " . implode(", ", $this->userBill) . "
			FROM user_bill b
			WHERE b.user_id=$user_id";
		$db->query($req);

		if(!isset($vars["ship_id"])) $ship_id = 1;
		else $ship_id = $vars["ship_id"];

		$req = "INSERT INTO order_ship (
			order_id, " . implode(", ", $this->userOrderShip) . ")
			SELECT '$order_id'," . implode(", ", $this->userShip) . "
			FROM user_ship b
			WHERE b.user_id=$user_id and b.ship_id=".$ship_id;
		$db->query($req);

		$cart = $_SESSION["cart"];
		for ($i=0;$i<sizeof($cart);$i++) {
			$product_id 		= $cart[$i]["product_id"];
			$label 					= (!get_magic_quotes_runtime() && !get_magic_quotes_gpc()) ? addslashes($cart[$i]["label"]) : $cart[$i]["label"];
			$price 					= $ps_product->get_price($cart[$i]["product_id"]);
			$product_price 	= $price["product_price"];
			$qty 						= $cart[$i]["quantity"];
			$req = "INSERT INTO order_items (order_id, items_number,
		  		product_id,	items_price, items_tax,	items_qty, items_label)
				VALUES ($order_id, $i, $product_id, $product_price, 19.6, $qty, '".addslashes($label)."')";
			$db->query($req);
		}
		return $order_id;
	}

	/**************************************************************************
	** name: modify()
	** description: modify user and order informations
	** parameters: int order_id, array cart
	** returns:
	***************************************************************************/

	function modify($user_id,$order_id,&$cart){
		global $db, $vars, $auth;
		$order_shipping = $_SESSION["order_shipping"];
		$order_discount = $_SESSION["order_discount"];
		$order_pdiscount= $auth["discount"];
		$order_div1 		= $vars["order_div1"];
		$order_div2 		= $vars["order_div2"];

		$req = "UPDATE orders
			SET order_shipping='$order_shipping', order_discount='$order_discount', order_pdiscount='$order_pdiscount',
			order_div1='$order_div1', order_div2='$order_div2', order_type='".$vars["mode_paiement"]."'
			WHERE order_id='$order_id'";
		$db->query($req);

		$req = "DELETE FROM order_bill WHERE order_id=$order_id";
		$db->query($req);
		$req = "DELETE FROM order_ship WHERE order_id=$order_id";
		$db->query($req);
		$db->query("DELETE FROM order_items WHERE order_id=$order_id");
		return $this->add($user_id, $cart, $order_id);
	}

	/**************************************************************************
	** name: validate()
	** description: modify user and order informations
	** parameters: int order_id, array cart
	** returns:
	***************************************************************************/

	function validate($user_id, $order_id, $user_email, $status, $mode_paiement="CB"){
		global $auth;
		$orderInfo 	= $this->read($order_id, $user_id);
	/*******************************************************************************
	* Envoi du mail
	*******************************************************************************/

	if($mode_paiement=="CB") $paiement = "Cette commande a été réglée par carte bleue.";
	else $paiement = "Pour valider cette commande, merci de nous envoyer votre chèque à l'adresse ci-dessous.
		\nLe Club Joanis - Château Val Joanis\n84120 Pertuis";

	$message = "" .
$orderInfo["order_bill_name"] . " " . $orderInfo["order_bill_first_name"]. ",

Nous avons bien enregistré votre commande N° " . $this->get_order_val($orderInfo["order_id"]) . " du " . $orderInfo["d5"] . "
Vous trouverez ci-dessous le détail de votre commande :

".
$this->details
."

$paiement

Adresse de facturation :\n\n" . $orderInfo["order_bill_gender"] . " " . $orderInfo["order_bill_name"] . " " . $orderInfo["order_bill_first_name"] . "\n" . $orderInfo["order_bill_company"] . "\n" . $orderInfo["order_bill_address1"] . " " . $orderInfo["order_bill_address2"] . "\n" . $orderInfo["order_bill_zip"] . " " . $orderInfo["order_bill_city"] . "\nTéléphones :\nDomicile : " . $orderInfo["order_bill_phone1"] . "\nBureau : " . $orderInfo["order_bill_phone2"] . "\nMobile : " . $orderInfo["order_bill_mobile"] . "
Adresse de livraison :\n\n" . $orderInfo["order_ship_gender"] . " " . $orderInfo["order_ship_name"] . " " . $orderInfo["order_ship_first_name"] . "\n" . $orderInfo["order_ship_company"] . "\n" . $orderInfo["order_ship_address1"] . " " . $orderInfo["order_ship_address2"] . "\n" . $orderInfo["order_ship_zip"] . " " . $orderInfo["order_ship_city"] . "\nTéléphones :\nDomicile : " . $orderInfo["order_ship_phone1"] . "\nBureau : " . $orderInfo["order_ship_phone2"] . "\nMobile : " . $orderInfo["order_ship_mobile"] . "
Livraison sous 10 jours ouvrables.\nNotez que notre transporteur vous contactera pour prendre rendez-vous avec vous.
Instructions de livraison :\n" . $orderInfo["order_div1"] . "
Message à joindre avec le colis :\n" . $orderInfo["order_div2"] . "
Toute l'équipe du Club Joanis vous remercie de votre confiance,\nLe Club Joanis
-------------------------------------\nLe Club Joanis - Château Val Joanis - 84120 Pertuis\nTél : 04 90 79 20 77 - Fax : 04 90 09 69 52
	";

		$this->change_status($order_id, $status, 0);
	  if($status!=1){
	  		//echo "test : ".$user_email;
			mail($user_email, "Le club Joanis : Confirmation de commande", $message,"From: ".FROM."\nReply-To: ".FROM."\nReturn-Path: <".FROM.">\n charset=iso-8859-1\"iso-8859-1\"\nContent-Transfer-Encoding: 8bit\r\n");

			$titre = $orderInfo["order_bill_name"] . " " .
				$orderInfo["order_bill_first_name"] . " " .
				$this->get_order_val($orderInfo["order_id"]);
			mail(FROM, $titre, $message, "From: ".FROM."\nReply-To: ".FROM."\nReturn-Path: <".FROM.">\nContent-Type: text/text; charset=utf-8\"UTF-8\"\nContent-Transfer-Encoding: 8bit\r\n");
			mail("b.rollee@val-joanis.com", $titre, $message, "From: ".FROM."\nReply-To: ".FROM."\nReturn-Path: <".FROM.">\nContent-Type: text/text; charset=utf-8\"UTF-8\"\nContent-Transfer-Encoding: 8bit\r\n");
			//mail("mouchard@vinternet.net", $titre, $message,"From: ".FROM."\nReply-To: ".FROM."\nReturn-Path: <".FROM.">\n charset=iso-8859-1\"iso-8859-1\"\nContent-Transfer-Encoding: 8bit\r\n");
		}
		return $orderInfo;
	}

	/**************************************************************************
	** name: modify_user()
	** description: modify user profile
	** parameters: array d, string page
	** returns: boolean
	***************************************************************************/

	function modify_user(&$d){
		global $db;
		$ship_id 			= $d["ship_id"];
		$user_id		 	= $d["user_id"];
		$password 		= (!get_magic_quotes_runtime() && !get_magic_quotes_gpc()) ? addslashes($d["password"]) : $d["password"];
		$user_email		= (!get_magic_quotes_runtime() && !get_magic_quotes_gpc()) ? addslashes($d["user_email"]) : $d["user_email"];

		foreach($this->userShip as $key => $val){
    	if($d["ship_id"] == 0)
    	  $valName = str_replace("ship_", "bill_", $val);
			else
			  $valName = $ship_id."#".$val;
      $updateShip[$val] = $d[$valName];
    }

		foreach($this->userBill as $key => $val){
    	$updateBill[$val] = $d[$val];
    }

		$req = "UPDATE user_bill
			set " .makeSQL($updateBill, "update"). "
			where user_id=$user_id";
		$db->query($req);

		if($d[$ship_id."#add_ship"]=="1"){
		  $updateShip["ship_id"] 	= $d["ship_id"];
		  $updateShip["ship_label"] 		= $d[$ship_id."#ship_label"];
		  $updateShip["user_id"] 	= $user_id;
			$req = "INSERT INTO user_ship " .makeSQL($updateShip, "insert"). "";
		}else{
			$req = "UPDATE user_ship
				set " .makeSQL($updateShip, "update"). "
				where user_id=$user_id and ship_id=$ship_id";
		}
		$db->query($req);

		$req = "UPDATE user
			set
			user_email='$user_email',
			username='$user_email'";
		if($password!="")
			$req .= " ,password='$password' ";
		$req .= " where user_id=$user_id";
		$db->query($req);

		return true;
	}

	/**************************************************************************
	** name: orderDetails()
	** description: get order details
	** parameters: int order_id, string print
	** returns: boolean or sring order_infomation
	***************************************************************************/

	function orderDetails ($order_id){
		$db = new DB;
		global $ps_cart, $auth;

		/*$req = "SELECT i.*, o.order_shipping, o.order_discount, o.order_pdiscount
			FROM order_items i, orders o
			WHERE i.order_id=$order_id
			AND o.order_id=i.order_id
			ORDER BY i.items_number";*/

		$req = "SELECT i. * , o.order_shipping, o.order_discount, o.order_pdiscount, u.discount_txt
			FROM order_items i, orders o, user u
			WHERE i.order_id=$order_id
			AND o.order_id = i.order_id
			AND o.user_id = u.user_id
			ORDER BY i.items_number";
		$db->query($req);
		$ps_cart->reset();

		while ($db->next_record()){
			$d = array();
			$d["product_id"]	= $db->f("product_id");
			$d["quantity"]		= $db->f("items_qty");
			$d["label"]				= $db->f("items_label");
			$d["price"]				= $db->f("items_price");
			$d["company"]			= $db->f("company");
			$order_shipping		= $db->f("order_shipping");
			$order_discount		= $db->f("order_discount");
			$user_discount		= $db->f("order_pdiscount");
			$discount_txt     = $db->f("discount_txt");
			$ps_cart->add($d);
      $total += $d["price"]*$d["quantity"];
      $total_qty += $d["quantity"];

			$cartItems .= $d["quantity"] . " x\t" . $d["label"] .
				"  - Prix : " . number_format($d["price"], 2, ',', ' ') . " euros TTC /btle\r";
		}
		$cartItems .= "\n";

		//if($order_discount>0) $cartItems .= "\nRemise quantitative : " . number_format($order_discount, 2, ',', ' ') . " euros TTC";

		//$user_discount = $auth["discount"];
		//$order_total = $order_shipping + $total - $order_discount;
		$user_discount = $total*($user_discount/100);
		//if($user_discount>0) $cartItems .= "\nRemise personnelle $discount_txt: " . number_format($user_discount, 2, ',', ' ') . " euros TTC";

		//$order_total = $order_shipping + $total - $order_discount - $user_discount;
		$order_total = $total;
		if($total<230){
			$cartItems .= "\nFrais de port : " . number_format($order_shipping, 2, ',', ' ') . " euros TTC";
			$order_total += $order_shipping;
		}
		else $cartItems .= "\nFrais de port : " . number_format(0, 2, ',', ' ') . " euros TTC";
		$cartItems .= "\nTotal : " . number_format($order_total, 2, ',', ' ') . " euros TTC";
		$this->details = $cartItems;
	}

	/**************************************************************************
	** name: change_status()
	** description: change order status
	** parameters: int order_id, int status, int order_number
	** returns: boolean
	***************************************************************************/

	function change_status($order_id,$status,$order_number){
		global $db;
		$update = "";

		if($order_number==0 && ($status=="2" || $status=="3")){
			$req = "SELECT max(order_number) as order_number FROM orders";
			$db->query($req);
			$db->next_record();
			$order_number = $db->f("order_number") + 1;
			$update .= ", order_number='" . $order_number . "'";
			$update .= ", order_valid_date=now()";
		}
		$req = "UPDATE orders set order_status='$status' " . $update . " WHERE order_id=$order_id";
		$db->query($req);
		return true;
	}

	/**************************************************************************
	** name: get_status()
	** description: get order status string value
	** parameters: order_status
	** returns: string status
	***************************************************************************/

	function get_status($order_status, $display=""){
		$status = array(
			"0" => array ("Attente paiement","Black","Bold"),
			"1" => array ("CB en echec","Navy","Normal"),
			"2" => array ("CB payée","Red","Normal"),
			"3" => array ("En livraison","Black","Normal"),
			"4" => array ("Livrée","Black","Normal"),
			"6" => array ("Supprimée","lightgrey","Normal")
			);
		if($display=="select"){
			$option = "";
			foreach($status as $i => $status){
				$option .= "\n<option value='$i'";
				if($order_status==$i) $option .= " selected";
				$option .= ">" . $status[0];
			}
			return $option;
		}else{
			return $status[$order_status];
		}
	}

	/**************************************************************************
	** name: get_order_val() & get_order_id()
	** description:  transform order num to string or int
	** parameters: mixed order_id
	** returns: mixed orderId
	***************************************************************************/

	function get_order_val($order_id){
		return sprintf("%06s", $order_id);
	}

	function get_order_id($order_id){
		return intval($order_id);
	}

	/**************************************************************************
	** name: read()
	** description:  get user order informations
	** parameters: int order_id
	** returns: array orderInfo
	***************************************************************************/
	function read($order_id, $user_id){
		$orderInfos = array();
		global $db;
		/*
			IFNULL(s.order_ship_gender, b.order_bill_gender) as order_ship_gender,
			IFNULL(s.order_ship_name, b.order_bill_name) as order_ship_name,
			IFNULL(s.order_ship_first_name, b.order_bill_first_name) as order_ship_first_name,
			IFNULL(s.order_ship_company, b.order_bill_company) as order_ship_company,
			IFNULL(s.order_ship_address1, b.order_bill_address1) as order_ship_address1,
			IFNULL(s.order_ship_address2, b.order_bill_address2) as order_ship_address2,
			IFNULL(s.order_ship_city, b.order_bill_city) as order_ship_city,
			IFNULL(s.order_ship_zip, b.order_bill_zip) as order_ship_zip,
			IFNULL(s.order_ship_phone1, b.order_bill_phone1) as order_ship_phone1,
			IFNULL(s.order_ship_phone2, b.order_bill_phone2) as order_ship_phone2,
			IFNULL(s.order_ship_mobile, b.order_bill_mobile) as order_ship_mobile,
			IFNULL(s.order_ship_fax, b.order_bill_fax) as order_ship_fax,
		*/

		$req = "
			SELECT
			o.order_id,
			o.order_status,
			o.order_number,
			o.order_shipping,
			o.order_discount,
			o.order_div1,
			o.order_div2,
			DATE_FORMAT(o.cre_date,'%d/%m/%Y') as d5,
			b.order_bill_gender,
			b.order_bill_name,
			b.order_bill_first_name,
			b.order_bill_company,
			b.order_bill_address1,
			b.order_bill_address2,
			b.order_bill_city,
			b.order_bill_zip,
			b.order_bill_phone1,
			b.order_bill_phone2,
			b.order_bill_mobile,
			b.order_bill_fax,
			s.order_ship_gender,
			s.order_ship_name,
			s.order_ship_first_name,
			s.order_ship_company,
			s.order_ship_address1,
			s.order_ship_address2,
			s.order_ship_city,
			s.order_ship_zip,
			s.order_ship_phone1,
			s.order_ship_phone2,
			s.order_ship_mobile,
			s.order_ship_fax,
			u.user_email
			FROM orders o
			LEFT JOIN user u on u.user_id=o.user_id
			LEFT JOIN order_bill b on o.order_id=b.order_id
			LEFT JOIN order_ship s on o.order_id=s.order_id
			WHERE o.order_id=$order_id AND o.user_id=$user_id";
		$db->query($req);
		$orderInfo = $db->next_array();
		$this->orderDetails($order_id);

		if(!$orderInfo["order_ship_name"]) $orderInfo["order_ship_gender"] = $orderInfo["order_bill_gender"];
		if(!$orderInfo["order_ship_name"]) $orderInfo["order_ship_first_name"] = $orderInfo["order_bill_first_name"];
		if(!$orderInfo["order_ship_name"]) $orderInfo["order_ship_company"] = $orderInfo["order_bill_company"];
		if(!$orderInfo["order_ship_name"]) $orderInfo["order_ship_address1"] = $orderInfo["order_bill_address1"];
		if(!$orderInfo["order_ship_name"]) $orderInfo["order_ship_address2"] = $orderInfo["order_bill_address2"];
		if(!$orderInfo["order_ship_name"]) $orderInfo["order_ship_zip"] = $orderInfo["order_bill_zip"];
		if(!$orderInfo["order_ship_name"]) $orderInfo["order_ship_city"] = $orderInfo["order_bill_city"];
		if(!$orderInfo["order_ship_name"]) $orderInfo["order_ship_phone1"] = $orderInfo["order_bill_phone1"];
		if(!$orderInfo["order_ship_name"]) $orderInfo["order_ship_phone2"] = $orderInfo["order_bill_phone2"];
		if(!$orderInfo["order_ship_name"]) $orderInfo["order_ship_mobile"] = $orderInfo["order_bill_mobile"];
		if(!$orderInfo["order_ship_name"]) $orderInfo["order_ship_name"] = $orderInfo["order_bill_name"];

		return $orderInfo;
	}
}
?>
