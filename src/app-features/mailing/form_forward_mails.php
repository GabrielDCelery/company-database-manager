<?php

require("../../../settings/settings.php");
require("../../../src/app-core/connect-to-database.php");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$forwarding_date = $request->forwardingDate;
if(!is_null($forwarding_date)){
	$forwarding_date = strtotime($forwarding_date);
	$forwarding_date = date('Y-m-d', $forwarding_date);
}
$forwarding_method = $request->forwardingMethod;
$id_list = $request->id;

foreach($id_list as $id){
	$q = 'UPDATE mailing SET ';
	$q .= 'forwarding_date = "' . $forwarding_date . '", ';
	$q .= 'forwarding_method = "' . $forwarding_method . '"';
	$q .= ' WHERE id = ';
	$q .= $id;

	$pdo->query($q);
}

echo("Data successfully overwritten!");

?>