<?php
/*
* CLASS DESCRIPTION : ps_login
*
* Login and user functions
*
* methods:
*       generatePassword()
*       getUser()
*       getPasswd()
*				checkuser()
*       userInfos()
*************************************************************************/
class ps_login {
	var $message = "";

	/**************************************************************************
	** name: generatePassword()
	** description: generates automatic password
	** parameters: int length
	** returns: string password
	***************************************************************************/
	function generatePassword ($length = 8)	{
	  // start with a blank password
	  $password = "";
	  // define possible characters
	  $possible = "0123456789bcdfghjkmnpqrstvwxyz";
	  // add random characters to $password until $length is reached
	  $i = 0;
		while ($i < $length) {
	    // pick a random character from the possible ones
	    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
	    // we don't want this character if it's already in the password
	    if (!strstr($password, $char)) {
	      $password .= $char;
	      $i++;
	    }
	  }
	  return $password;
	}

	/**************************************************************************
	** name: getUser()
	** description: retrieve existing user
	** parameters: string username, string passwd
	** returns: boolean
	***************************************************************************/
	function getUser($username, $passwd){
		global $db;
		global $auth;
		$verif_username = "
			SELECT u.user_id, u.perms, b.bill_gender, b.bill_name, b.bill_first_name, u.password, u.discount, u.discount_txt
			FROM user u , user_bill b
			WHERE u.user_email='$username' and u.password='$passwd'
			AND u.user_id=b.user_id";

		$db->query($verif_username);
		if($db->next_record()){
			$auth["user_id"] 			= $db->f("user_id");
			$auth["email"] 				= $username;
			$auth["perms"] 				= $db->f("perms");
			$auth["first_name"] 	= $db->f("bill_first_name");
			$auth["last_name"] 		= $db->f("bill_name");
			$auth["user_gender"] 	= $db->f("bill_gender");
			$auth["discount"]			= $db->f("discount");
			$auth["discount_txt"]	= $db->f("discount_txt");

			$db->query("UPDATE user set user_last_login=now(),
			  user_nb_login=user_nb_login+1
			  WHERE user_id=".$auth["user_id"]);
			$_SESSION["auth"] = $auth;
	 		return true;
		}else{
		  unset($_SESSION["auth"]);
			return false;
		}
	}

	/**************************************************************************
	** name: checkuser()
	** description: check if user is valid or creates it
	** parameters: string user_email, string password
	** returns: string message
	***************************************************************************/
	function checkuser($user_email, $password, $pageDir){
		global $db, $vars, $urlSession;

		if(!$this->getUser($user_email, $password)){
			$verif_email 	= "SELECT count(*) as ct from user where user_email='$user_email'";
			$db->query($verif_email);

			// user already exists
			if($db->next_record() && $db->f('ct')>0){
				$err = sprintf(LOGIN_EXISTS,$user_email);
				return $err;

			// add new user and send email
			}else if($vars["action"]=="add"){
        $password = $this->generatePassword(6);
				$req = "INSERT INTO user
				(cre_date, user_email_sub, user_email, perms, username, password)
					values (
					now(),
					'Oui',
					'$user_email',
					1,
					'$user_email',
					'$password')";
				$db->query($req);
				$user_id = $db->inserted_key();
				$db->query("INSERT INTO user_bill (user_id, cre_date, bill_name, bill_first_name) values ($user_id, now(),
					'".$vars["user_first_name"]."',
					'".$vars["user_name"]."')");
				$db->query("INSERT INTO user_ship (user_id, cdate) values ($user_id, now())");
				// send mail tu user
				$message = sprintf(WELCOME_MSG, $vars["user_name"], $vars["user_first_name"], $user_email, $password);
				ps_mail($user_email, WELCOME_TITLE, $message, "From: " . FROM . "\r\n");
				$ok = "Un email contenant vos codes d'accès a été envoyé à l'adresse $user_email.";
				return $ok;
			}else{
				$err = LOGIN_UNKNOWN;
				return $err;
			}

		// login OK
		}else{
		  if($pageDir=="") $pageDir = "index.php";
			header("Location: $pageDir$urlSession");
			exit();
		}
	}

	/**************************************************************************
	** name: getPasswd()
	** description: retrieve user password and send it to email adress
	** parameters: string user_email
	** returns: string message
	***************************************************************************/
	function getPasswd($user_email){
	  global $db;

		$verif_username = "
				SELECT u.user_id, u.perms, b.bill_gender, b.bill_name, b.bill_first_name, u.password
				FROM user u
				LEFT JOIN user_bill b using(user_id)
				WHERE username='$user_email'";
		$db->query($verif_username);
		if($db->next_record()){
			$user_first_name 	= $db->f("bill_first_name");
			$user_name 				= $db->f("bill_name");
			$password 				= $db->f("password");
      $msg_mdp = sprintf(PASSWD_MSG,$user_first_name,$user_name,$user_email,$password);
			ps_mail($user_email, PASSWD_TITLE, $msg_mdp ,"From: ".FROM."\r\n");
			$err = sprintf(LOGIN_CONFIRM,$user_email);
		}else{
		  $err = LOGIN_UNKNOWN;
		}
		return $err;
	}

	/**************************************************************************
	** name: userInfos()
	** description: retrives user profile
	** parameters: int user_id
	** returns: array userInfos
	***************************************************************************/
	function userInfos($user_id){
		global $db;
		$req = "
			SELECT *, date_format(user_last_login, '%d/%m/%Y à %H:%i:%s') as user_last_login
			FROM user u
			LEFT JOIN user_bill b on u.user_id=b.user_id
			LEFT JOIN user_ship s on u.user_id=s.user_id
			WHERE u.user_id=$user_id";
		$db->query($req);
		return $db->next_array();
	}
}
?>
