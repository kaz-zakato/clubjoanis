<?php
session_start();
$_SESSION["auth"] = "";
$_SESSION["auth"]["perms"]  = 1;
$_SESSION["auth"]["user_id"]= 0;
header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/index.php$urlSession");
?>
