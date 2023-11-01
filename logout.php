<?php

session_start();

// Unset all session avariables
$_SESSION = array();

setcookie(session_name(), "", time() - 360);
session_destroy();


// Redirect to index
header("Location: index.php");
?>