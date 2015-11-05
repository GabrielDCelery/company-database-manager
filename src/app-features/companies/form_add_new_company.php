<?php

require("../../../settings/settings.php");
require("../../../src/app-core/connect-to-database.php");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$company_name = $request->company_name;
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

$q1 = 'INSERT INTO companies(company_name, contract_status) VALUES(:cname, :cstatus)';
$preparedstatement_1 = $pdo->prepare($q1);
$preparedstatement_1->execute(array(
	'cname' => $company_name,
	'cstatus' => 1

));

$q2 = 'SELECT id FROM companies WHERE companies.company_name = :company_name';
$preparedstatement_2 = $pdo->prepare($q2);
$preparedstatement_2->execute(array(
	'company_name' => $company_name
));
$results_2 = $preparedstatement_2->fetchAll(PDO::FETCH_ASSOC);

$company_id = $results_2[0]["id"];

$q3 = 'INSERT INTO companies_detailed(';
$q3 .= 'company_id, ';
$q3 .= 'starting_date, ';
$q3 .= 'ending_date, ';
$q3 .= 'company_phone, ';
$q3 .= 'company_email, ';
$q3 .= 'invoice_number, ';
$q3 .= 'service_provider, ';

$q3 .= 'transfer_date, ';
$q3 .= 'invoice_date, ';
$q3 .= 'payment_method, ';
$q3 .= 'account_number, ';

$q3 .= 'price_of_serv_num, ';
$q3 .= 'price_of_serv_let, ';
$q3 .= 'company_address, ';
$q3 .= 'company_register_id, ';
$q3 .= 'company_tax_id, ';
$q3 .= 'postal_number, ';
$q3 .= 'postal_service, ';
$q3 .= 'postal_name, ';
$q3 .= 'postal_address, ';
$q3 .= 'manager_name, ';
$q3 .= 'manager_status, ';
$q3 .= 'manager_id, ';
$q3 .= 'manager_mother_name, ';
$q3 .= 'manager_address, ';
$q3 .= 'document_holder, ';
$q3 .= 'document_holder_address ';

$q3 .= ') VALUES(:company_id,
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

$preparedstatement_3 = $pdo->prepare($q3);
$preparedstatement_3->execute(array(
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

echo("Company successfully added to database");

?>