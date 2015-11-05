<?php

/* This file is required after the settings.php file */

/*****************************************************************************
CONNECTING TO THE DATABASE WITH THE CURRENT SESSSION
*****************************************************************************/

/* If the users also have editing priviliges in the database uncomment this code

if(!isset($_SESSION)) { 
	session_start();
}

if(isset($_SESSION['login_username'])){
	$admin_username = $_SESSION['login_username'];
	$admin_username= $_SESSION['login_password'];
}
*/

$pdo = new PDO('mysql:dbname=' . $dbname . ';host=' . $host, $admin_username, $admin_password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));


?>