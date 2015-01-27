<?php
error_reporting(E_ALL);
header("Location: http://" . $_SERVER['HTTP_HOST']
                     . dirname($_SERVER['PHP_SELF'])
                     . "/product.php?list=1");
//                     . "/orders.php?status=0");
?>
