<?php

require("../../../settings/settings.php");
require("../../../src/app-core/connect-to-database.php");

$postdata = file_get_contents("php://input");
$requests = json_decode($postdata);

foreach($requests as $request){
	$mail_id = $request->mail_id;
	$sender_name = $request->sender_name;
	$sender_address = $request->sender_address;
	$receiving_date = $request->receiving_date;
	$forwarding_date = $request->forwarding_date;
	$forwarding_method = $request->forwarding_method;

	if(!is_null($receiving_date)){
		$receiving_date = strtotime($receiving_date);
		$receiving_date = date('Y-m-d', $receiving_date);
	}

	if(!is_null($forwarding_date)){
		$forwarding_date = strtotime($forwarding_date);
		$forwarding_date = date('Y-m-d', $forwarding_date);
	}

	$q = 'UPDATE mailing SET ';
	$q .= 'sender_name = "' . $sender_name . '", ';
	$q .= 'sender_address = "' . $sender_address . '", ';
	$q .= 'receiving_date = "' . $receiving_date . '", ';
	$q .= 'forwarding_date = "' . $forwarding_date . '", ';
	$q .= 'forwarding_method = "' . $forwarding_method . '"';
	$q .= ' WHERE mailing.id = ';
	$q .= $mail_id;

	$pdo->query($q);
}

echo("Data successfully overwritten");

?>