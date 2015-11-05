<?php

require("../../../settings/settings.php");
require("../../../src/app-core/connect-to-database.php");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$contracts_id_list = $request->id;

$query_string = 'SELECT company_id
	FROM companies_detailed
	WHERE id = "' . $contracts_id_list[0] . '"';

$query = $pdo->query($query_string);
$result = $query->fetchAll(PDO::FETCH_ASSOC);

$company_id = $result[0]['company_id'];

$query_string = 'SELECT * FROM ';
$query_string .= '(';
$query_string .= 'SELECT companies.id as id,
	company_name,
	contract_status,
	company_email,
	company_phone,
	starting_date,
	ending_date,
	company_id,
	postal_number,
	invoice_number,
	service_provider,
	price_of_serv_num,
	price_of_serv_let,
	transfer_date,
	invoice_date,
	payment_method,
	account_number,
	company_address,
	company_register_id,
	company_tax_id,
	postal_service,
	postal_name,
	postal_address,
	manager_name,
	manager_status,
	manager_id,
	manager_mother_name,
	manager_address,
	document_holder,
	document_holder_address
	FROM companies
	INNER JOIN companies_detailed
	ON companies.id = companies_detailed.company_id
	WHERE companies_detailed.company_id = "' . $company_id . '"';
$query_string .= ' ORDER BY ending_date DESC';
$query_string .= ') as detailed_info';
$query_string .= ' GROUP BY id = "' . $company_id . '"';

$query = $pdo->query($query_string);
$result = $query->fetchAll(PDO::FETCH_ASSOC);

echo(json_encode($result));

?>