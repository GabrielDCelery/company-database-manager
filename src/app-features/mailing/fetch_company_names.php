<?php

require("../../../settings/settings.php");
require("../../../src/app-core/connect-to-database.php");

$result = $pdo->query('SELECT company_name FROM companies');
$rows = $result->fetchAll(PDO::FETCH_ASSOC);
echo(json_encode($rows));

?>