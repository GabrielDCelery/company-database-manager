<?php

require("../../../settings/settings.php");
require("../../../src/app-core/connect-to-database.php");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$company_name = $request->companyName;
$starting_date = $request->startingDate;
$ending_date = $request->endingDate;

if($starting_date > $ending_date){
	$temp_date = $starting_date;
	$starting_date = $ending_date;
	$ending_date = $temp_date;
}

if(!is_null($starting_date)){
	$starting_date = strtotime($starting_date);
	$starting_date = date('Y-m-d', $starting_date);
}
if(!is_null($ending_date)){
	$ending_date = strtotime($ending_date);
	$ending_date = date('Y-m-d', $ending_date);
}

$non_forwarded = $request->nonForwarded;
$forwarded = $request->forwarded;
$has_postal_service = $request->hasPostalService;
$doesnt_have_postal_service = $request->doesntHavePoastalService;

/* Filtering the selected company name from the table*/
$subquery1 = '(';
$subquery1 .= 'SELECT
	company_id,
	company_name,
	postal_service,
	ending_date
	FROM companies
	INNER JOIN companies_detailed ON companies.id = companies_detailed.company_id';

if ($company_name != "" || $company_name != null){
	$subquery1 .= ' WHERE company_name = "' . $company_name . '"';
} else {
	$subquery1 .= ' WHERE 1';
}

$subquery1 .= ' ORDER BY ending_date DESC';
$subquery1 .= ')';

/* Arranging the filtered table by date*/
$subquery2 = '(';
$subquery2 .= 'SELECT * FROM ' . $subquery1 . ' AS select_companies GROUP BY company_name';
$subquery2 .= ')';

/* Filtering the table whether the company has postal service or not */
$subquery3 = '(';
$subquery3 .= 'SELECT * FROM ' . $subquery2 . ' AS check_postal_service ';

if($has_postal_service == true && $doesnt_have_postal_service == true){
	$subquery3 .= ' WHERE postal_service = 1 OR postal_service = 0';
};

if($has_postal_service == true && $doesnt_have_postal_service == false){
	$subquery3 .= ' WHERE postal_service = 1 ';
};

if($has_postal_service == false && $doesnt_have_postal_service == true){
	$subquery3 .= ' WHERE postal_service = 0 ';
};

if($has_postal_service == false && $doesnt_have_postal_service == false){
	$subquery3 .= ' WHERE postal_service = 1 AND postal_service = 0';
};

$subquery3 .= ')';

$mainquery = 'SELECT mailing.id AS mail_id,
	company_name,
	sender_name,
	sender_address,
	receiving_date,
	forwarding_date,
	forwarding_method
	FROM mailing
	INNER JOIN ';

$mainquery .= $subquery3;
$mainquery .= 'AS select_mails ON mailing.company_id = select_mails.company_id';
$mainquery .= ' WHERE 1 ';

if ($starting_date !== null){
	$mainquery .= ' AND receiving_date >= "' . $starting_date . '" ';
}

if ($ending_date !== null){
	$mainquery .= ' AND receiving_date <= "' . $ending_date . '" ';
}

if($non_forwarded == true && $forwarded == true){
	$mainquery .= ' AND (forwarding_date >= "1970-01-01" OR forwarding_date <= "1970-01-01" OR forwarding_date IS NULL OR forwarding_date IS NOT NULL)';
};

if($non_forwarded == true && $forwarded == false){
	$mainquery .= ' AND (forwarding_date <= "1970-01-01" OR forwarding_date IS NULL)';
};

if($non_forwarded == false && $forwarded == true){
	$mainquery .= ' AND (forwarding_date >= "1970-01-01")';
};

if($non_forwarded == false && $forwarded == false){
	$mainquery .= ' AND (forwarding_date >= "1970-01-01" AND forwarding_date <= "1980-01-01")';
};


$query = $pdo->query($mainquery);
$result = $query->fetchAll(PDO::FETCH_ASSOC);
echo(json_encode($result));

?>