<?php

require("../../../settings/settings.php");
require("../../../src/app-core/connect-to-database.php");

$query = $pdo->query('SELECT sender_name, sender_address FROM mail_addresses');
$result = $query->fetchAll(PDO::FETCH_ASSOC);
echo(json_encode($result));
?>