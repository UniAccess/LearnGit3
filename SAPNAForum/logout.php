<?php
session_start();
//foreach($_SESSION as $a)echo $a;
//unset($_SESSION['Username']);

session_destroy();
setcookie("Username",$user,time()-36000);
header('Location: index.php');
?>