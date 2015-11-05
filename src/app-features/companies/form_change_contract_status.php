<?php

require("../../../settings/settings.php");
require("../../../src/app-core/connect-to-database.php");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$company_id_list = $request->id;

$query_companies_statuses = 'SELECT companies.id AS id,
	contract_status
	FROM companies
	INNER JOIN companies_detailed
	ON companies.id = companies_detailed.company_id
	WHERE companies_detailed.id = ';

$query_companies_statuses .= $company_id_list[0];

if(count($company_id_list) > 1){
	for($i = 1; $i < count($company_id_list); $i++){
		$query_companies_statuses .= ' OR companies_detailed.id = ';
		$query_companies_statuses .= $company_id_list[$i];
	}
}

$query_companies_statuses .= ' GROUP BY id';

$query_for_statuses = $pdo->query($query_companies_statuses);
$result_of_company_statuses = $query_for_statuses->fetchAll(PDO::FETCH_ASSOC);

for($i = 0; $i < count($result_of_company_statuses); $i++){

	$query_update_status = 'UPDATE companies SET contract_status = ';

	if($result_of_company_statuses[$i]['contract_status'] == 1){
		$query_update_status .= 0;
	} else {
		$query_update_status .= 1;
	}

	$query_update_status .= ' WHERE id = ';
	$query_update_status .= $result_of_company_statuses[$i]['id'];
	$pdo->query($query_update_status);
}

echo("Status of companies changed!");

?>