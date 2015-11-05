<?php

require("../../../settings/settings.php");
require("../../../src/app-core/connect-to-database.php");

$postdata = file_get_contents("php://input");
$decodedpostdata = json_decode($postdata);

$company_name = $decodedpostdata->companyName;
$receiving_date = $decodedpostdata->receivingDate;

if(!is_null($receiving_date)){
	$receiving_date = strtotime($receiving_date);
	$receiving_date = date('Y-m-d', $receiving_date);
}

$list_of_mails = $decodedpostdata->mails;

$querystring_1 = 'SELECT id FROM companies WHERE companies.company_name = "' . $company_name . '"';
$query = $pdo->query($querystring_1);
$results = $query->fetchAll(PDO::FETCH_ASSOC);
$company_id = $results[0]["id"];

foreach($list_of_mails as $mail){
	$sender_name = $mail->senderName;
	$sender_address = $mail->senderAddress;

	$querystring = 'INSERT INTO mailing (company_id, sender_name, sender_address, receiving_date) VALUES ';
	$querystring .= '("' . $company_id . '", "' . $sender_name . '", "' . $sender_address . '", "' . $receiving_date . '")';

	$pdo->exec($querystring);
}

echo("Email(s) added successfully");

?>