<?php
session_start();

// Remove and delete session data
unset($_SESSION['loggedIn']);
$_SESSION = array();
session_destroy();

header('Location:login.php');

?>