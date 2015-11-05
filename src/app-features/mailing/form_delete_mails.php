<?php

require("../../../settings/settings.php");
require("../../../src/app-core/connect-to-database.php");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$mail_id_list = $request->id;

foreach($mail_id_list as $mail_id){
	$q = 'DELETE FROM mailing WHERE id = ';
	$q .= $mail_id;
	$pdo->query($q);
}

echo("Data successfully overwritten!");

?>