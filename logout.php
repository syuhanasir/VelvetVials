<?php
session_start(); 

session_unset();

session_destroy();

if (isset($_COOKIE['userEmail'])) {
    setcookie('userEmail', '', time() - 3600, '/'); 
}

header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: 0"); 

header("Location: index.php");
exit();
?>
