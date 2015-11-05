<?php

require("../../../settings/settings.php");
require("../../../src/app-core/connect-to-database.php");

$querystring = 'SELECT company_name FROM companies';
$preparedstatement = $pdo->prepare($querystring);
$preparedstatement->execute();
$results = $preparedstatement->fetchAll(PDO::FETCH_ASSOC);
echo(json_encode($results));

?>