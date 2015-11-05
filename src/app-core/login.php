<?php

/*****************************************************************************
DEPENDENCIES
*****************************************************************************/

require("../../settings/settings.php");
require("connect-to-database.php");

/*****************************************************************************
GETTING THE FORM DATA
*****************************************************************************/

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$login_username = $request->userName;
$login_password = $request->password;

/*****************************************************************************
CHECKING THE DATABASE FOR PASSWORD MATCH
*****************************************************************************/

$querystring = 'SELECT password FROM users WHERE username = :login_username';
$preparedstatement = $pdo->prepare($querystring);
$preparedstatement->execute(array("login_username" => $login_username));
$results = $preparedstatement->fetchAll(PDO::FETCH_ASSOC);

if($results != null){
	if($results[0]['password'] == $login_password){
		if(!isset($_SESSION)) { 
			session_start();
		}
		$_SESSION['login_username'] = $login_username;
		$_SESSION['login_password'] = $login_password;
		echo(1);
	} else {
		echo(0);
	}
} else {
	echo(0);
}


?>