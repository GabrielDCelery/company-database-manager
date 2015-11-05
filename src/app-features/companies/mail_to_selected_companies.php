<?php
require("../../../settings/settings.php");
require("../../../src/app-core/connect-to-database.php");

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$company_id_list = $request->id;
$type = $request->type;
$subject = $request->subject;
$subject_copy = $subject;
$header = 'From: info@szekhelyszolgaltatas.com' . "\r\n" .
    'Reply-To: info@szekhelyszolgaltatas.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

$list_of_companies = "";

switch($type){
	case "contractexpired":
	
		for($i = 0; $i < count($company_id_list); $i++){
			$q1 = 'SELECT company_id, company_email, manager_name, ending_date, price_of_serv_num, service_provider FROM companies_detailed WHERE id = ' . $company_id_list[$i];
			$query_1 = $pdo->query($q1);
			$result_1 = $query_1->fetchAll(PDO::FETCH_ASSOC);
			$id = $result_1[0]['company_id'];

			$q2 = 'SELECT company_name FROM companies WHERE id = ' . $id;
			$query_2 = $pdo->query($q2);
			$result_2 = $query_2->fetchAll(PDO::FETCH_ASSOC);

			$to = $result_1[0]['company_email'];

			$list_of_companies .= $result_2[0]["company_name"];
			$list_of_companies .= "\r\n";

			$message = "Tisztelt " . $result_1[0]["manager_name"] . "!";
			$message .= "\r\n";
			$message .= "A(z) " . $result_2[0]["company_name"] . "-vel kötött székhely szerződés díjfordulója a következő: ";
			$message .= $result_1[0]["ending_date"];
			$message .= "\r\n";
			$message .= "Kérem a székhely díjat átutalni, vagy személyesen behozni a Miklós utcába!";
			$message .= "\r\n";
			$message .= "Összeg: " . $result_1[0]["price_of_serv_num"];
			$message .= "\r\n";
			$message .= "Ha magánszámláról utal: ";
			$message .= "\r\n";
			$message .= "számlatulajdonos: Zeller-Daczi Gábor";
			$message .= "\r\n";
			$message .= "számlaszám: 14100206-11366349-01000006";
			$message .= "\r\n";
			$message .= "Ha céges számláról utal: ";
			$message .= "\r\n";
			switch($result_1[0]["service_provider"]){
				case "Zeller és Zeller Kft.":
					$message.= "számlatulajdonos: Zeller és Zeller Kft.";
					$message .= "\r\n";
					$message .= "14100206-15987449-01000002";
					break;
				case "World Top Sport Bt.":
					$message.= "számlatulajdonos: World Top Sport Bt.";
					$message .= "\r\n";
					$message .= "65700017-10131178-00000000";
					break;
				default:
					$message.= "Default";
			}
			$message .= "\r\n";
			$message .= "Üdvözlettel";
			$message .= "\r\n";
			$message .= "Zeller-Daczi Gábor / Zeller Ildikó Anna";
			$message .= "\r\n";
			$message .= "1035 Budapest, Miklós utca 13. 8/42";
			$message .= "\r\n";
			$message .= "06-70/777-51-82";
			$message .= "\r\n";
			$message .= "06-1/501-40-58";
			$message .= "\r\n";
			$message .= "http://szekhelyszolgaltatas.com";
			$message .= "\r\n";

			$subject = iconv("UTF-8", "ISO-8859-2", $subject);
			$message = iconv("UTF-8", "ISO-8859-2", $message);


			mail($to, $subject, $message, $header);
		}
	break;
	case "lastwarning":
	
		for($i = 0; $i < count($company_id_list); $i++){
			$q1 = 'SELECT company_id, company_email, manager_name, ending_date, price_of_serv_num, service_provider FROM companies_detailed WHERE id = ' . $company_id_list[$i];
			$query_1 = $pdo->query($q1);
			$result_1 = $query_1->fetchAll(PDO::FETCH_ASSOC);
			$id = $result_1[0]['company_id'];

			$q2 = 'SELECT company_name FROM companies WHERE id = ' . $id;
			$query_2 = $pdo->query($q2);
			$result_2 = $query_2->fetchAll(PDO::FETCH_ASSOC);

			$list_of_companies .= $result_2[0]["company_name"];
			$list_of_companies .= "\r\n";

			$to = $result_1[0]['company_email'];

			$message = "Tisztelt " . $result_1[0]["manager_name"] . "!";
			$message .= "\r\n";
			$message .= "A(z) " . $result_2[0]["company_name"] . " székhely szerződése lejárt.";
			$message .= "\r\n";
			$message .= "Kérem a székhely díjat három napon belül rendezni, ellenkező esetben a továbbiakban nem veszünk át levelet a székhelyen, a cég nevét a homlokzatról eltávolítjuk és értesítjük az illetékes hatóságokat a cég székhelyének megszűnéséről.";
			$message .= "\r\n";
			$message .= "Összeg: " . $result_1[0]["price_of_serv_num"];
			$message .= "\r\n";
			$message .= "Ha magánszámláról utal: ";
			$message .= "\r\n";
			$message .= "számlatulajdonos: Zeller-Daczi Gábor";
			$message .= "\r\n";
			$message .= "számlaszám: 14100206-11366349-01000006";
			$message .= "\r\n";
			$message .= "Ha céges számláról utal: ";
			$message .= "\r\n";
			switch($result_1[0]["service_provider"]){
				case "Zeller és Zeller Kft.":
					$message.= "számlatulajdonos: Zeller és Zeller Kft.";
					$message .= "\r\n";
					$message .= "14100206-15987449-01000002";
					break;
				case "World Top Sport Bt.":
					$message.= "számlatulajdonos: World Top Sport Bt.";
					$message .= "\r\n";
					$message .= "65700017-10131178-00000000";
					break;
				default:
					$message.= "Default";
			}
			$message .= "\r\n";
			$message .= "Üdvözlettel";
			$message .= "\r\n";
			$message .= "Zeller-Daczi Gábor / Zeller Ildikó Anna";
			$message .= "\r\n";
			$message .= "1035 Budapest, Miklós utca 13. 8/42";
			$message .= "\r\n";
			$message .= "06-70/777-51-82";
			$message .= "\r\n";
			$message .= "06-1/501-40-58";
			$message .= "\r\n";
			$message .= "http://szekhelyszolgaltatas.com";
			$message .= "\r\n";

			$subject = iconv("UTF-8", "ISO-8859-2", $subject);
			$message = iconv("UTF-8", "ISO-8859-2", $message);

			mail($to, $subject, $message, $header);
		}
	
	break;
}


$toAdmin = 'info@szekhelyszolgaltatas.com';

$subjectToAdmin = 'Levél ügyfeleknek kiküldve';

$headerToAdmin = 'From: info@szekhelyszolgaltatas.com' . "\r\n" .
    'Reply-To: info@szekhelyszolgaltatas.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

$messageToAdmin = "Levél tárgya: " . $subject_copy;
$messageToAdmin .= "\r\n";
$messageToAdmin .= $list_of_companies;

$subjectToAdmin = iconv("UTF-8", "ISO-8859-2", $subjectToAdmin);
$messageToAdmin = iconv("UTF-8", "ISO-8859-2", $messageToAdmin);

mail($toAdmin, $subjectToAdmin, $messageToAdmin, $headerToAdmin);

?>