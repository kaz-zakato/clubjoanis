<?php
if(!isset($_GET["nosession"])){
	session_start();
	$sepUrl = (strpos($_SERVER["PHP_SELF"], "?")!==false) ? "&" : "?";

	if (!isset($_COOKIE[session_name()])){
		$urlSession = $sepUrl . session_name() . "=" . session_id();
	}else{
	  $urlSession = $sepUrl . "a=";
	}
}else{
  $urlSession = $sepUrl . "a=";
}

ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);
//error_reporting(0);

if ($HTTP_POST_VARS) {
  $vars = $HTTP_POST_VARS;
}else if ($HTTP_GET_VARS) {
  $vars = $HTTP_GET_VARS;
}else if($_POST){
	$vars = $_POST;
}else if($_GET){
	$vars = $_GET;
}

if(is_array($vars)){
	foreach($vars as $ky => $vl){
		$$key = $vl;
	}
}

$url				= explode("/", preg_replace("/.php|.php/", "", $_SERVER["PHP_SELF"]));
//echo $url[1];
define("THIS_PAGE",end($url));

function ps_mail($email, $title, $message, $from){
	mail($email, $title, $message, $from);
}
?>
