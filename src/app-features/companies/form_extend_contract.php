<?php

require("../../../settings/settings.php");
require("../../../src/app-core/connect-to-database.php");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$company_name = $request->company_name;
$company_id = $request->company_id;
$starting_date = $request->starting_date;
$ending_date = $request->ending_date;
if(!is_null($starting_date)){
	$starting_date = strtotime($starting_date);
	$starting_date = date('Y-m-d', $starting_date);
}
if(!is_null($ending_date)){
	$ending_date = strtotime($ending_date);
	$ending_date = date('Y-m-d', $ending_date);
}
$company_phone = $request->company_phone;
$company_email = $request->company_email;
$invoice_number = $request->invoice_number;
$service_provider = $request->service_provider;
$transfer_date = $request->transfer_date;
$invoice_date = $request->invoice_date;
if(!is_null($transfer_date)){
	$transfer_date = strtotime($transfer_date);
	$transfer_date = date('Y-m-d', $transfer_date);
}
if(!is_null($invoice_date)){
	$invoice_date = strtotime($invoice_date);
	$invoice_date = date('Y-m-d', $invoice_date);
}
$payment_method = $request->payment_method;
$account_number = $request->account_number;
$price_of_serv_num = $request->price_of_serv_num;
$price_of_serv_let = $request->price_of_serv_let;
$company_address = $request->company_address;
$company_register_id = $request->company_register_id;
$company_tax_id = $request->company_tax_id;
$postal_number = $request->postal_number;
$postal_service = $request->postal_service;
$postal_name = $request->postal_name;
$postal_address = $request->postal_address;
$manager_name = $request->manager_name;
$manager_status = $request->manager_status;
$manager_id = $request->manager_id;
$manager_mother_name = $request->manager_mother_name;
$manager_address = $request->manager_address;
$document_holder = $request->document_holder;
$document_holder_address = $request->document_holder_address;

$querystring = 'INSERT INTO companies_detailed(';
$querystring .= 'company_id, ';
$querystring .= 'starting_date, ';
$querystring .= 'ending_date, ';
$querystring .= 'company_phone, ';
$querystring .= 'company_email, ';
$querystring .= 'invoice_number, ';
$querystring .= 'service_provider, ';
$querystring .= 'transfer_date, ';
$querystring .= 'invoice_date, ';
$querystring .= 'payment_method, ';
$querystring .= 'account_number, ';
$querystring .= 'price_of_serv_num, ';
$querystring .= 'price_of_serv_let, ';
$querystring .= 'company_address, ';
$querystring .= 'company_register_id, ';
$querystring .= 'company_tax_id, ';
$querystring .= 'postal_number, ';
$querystring .= 'postal_service, ';
$querystring .= 'postal_name, ';
$querystring .= 'postal_address, ';
$querystring .= 'manager_name, ';
$querystring .= 'manager_status, ';
$querystring .= 'manager_id, ';
$querystring .= 'manager_mother_name, ';
$querystring .= 'manager_address, ';
$querystring .= 'document_holder, ';
$querystring .= 'document_holder_address ';
$querystring .= ') VALUES(:company_id,
	:starting_date,
	:ending_date,
	:company_phone,
	:company_email,
	:invoice_number,
	:service_provider,
	:transfer_date,
	:invoice_date,
	:payment_method,
	:account_number,
	:price_of_serv_num,
	:price_of_serv_let,
	:company_address,
	:company_register_id,
	:company_tax_id,
	:postal_number,
	:postal_service,
	:postal_name,
	:postal_address,
	:manager_name,
	:manager_status,
	:manager_id,
	:manager_mother_name,
	:manager_address,
	:document_holder,
	:document_holder_address)';

$preparedstatement = $pdo->prepare($querystring);
$preparedstatement->execute(array(
	'company_id' => $company_id,
	'starting_date' => $starting_date,
	'ending_date' => $ending_date,
	'company_phone' => $company_phone,
	'company_email' => $company_email,
	'invoice_number' => $invoice_number,
	'service_provider' => $service_provider,
	'transfer_date' => $transfer_date,
	'invoice_date' => $invoice_date,
	'payment_method' => $payment_method,
	'account_number' => $account_number,
	'price_of_serv_num' => $price_of_serv_num,
	'price_of_serv_let' => $price_of_serv_let,
	'company_address' => $company_address,
	'company_register_id' => $company_register_id,
	'company_tax_id' => $company_tax_id,
	'postal_number' => $postal_number,
	'postal_service' => $postal_service,
	'postal_name' => $postal_name,
	'postal_address' => $postal_address,
	'manager_name' => $manager_name,
	'manager_status' => $manager_status,
	'manager_id' => $manager_id,
	'manager_mother_name' => $manager_mother_name,
	'manager_address' => $manager_address,
	'document_holder' => $document_holder,
	'document_holder_address' => $document_holder_address
));

echo(var_dump($transfer_date));

?>