<?php

/*****************************************************************************
DEPENDENCIES
*****************************************************************************/

require("../../../src/dependencies/vendor/autoload.php");
require("../../../settings/settings.php");
require("../../../src/app-core/connect-to-database.php");

/*****************************************************************************
GETTING THE FORM DATA AND FORMATTING IT
*****************************************************************************/

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$company_name = $request->company_name;
$manager_name = $request->manager_name;
$manager_address = $request->manager_address;
$company_phone = $request->company_phone;
$company_email = $request->company_email;

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

$postal_name = $request->postal_name;
$postal_address = $request->postal_address;
$document_holder = $request->document_holder;
$document_holder_address = $request->document_holder_address;

/*****************************************************************************
CREATING THE DOCUMENT TEMPLATE
*****************************************************************************/

$phpWord = new \PhpOffice\PhpWord\PhpWord();
$section = $phpWord->addSection();

/*****************************************************************************
FONTS
*****************************************************************************/

$baseFont = 'baseText';
$phpWord->addFontStyle(
    $baseFont,
    array('name' => 'Times New Roman', 'size' => 13, 'color' => '000000', 'bold' => false)
);

$titleFont = 'titleText';
$phpWord->addFontStyle(
    $titleFont,
    array('name' => 'Times New Roman', 'size' => 15, 'color' => '000000', 'bold' => true)
);

/*****************************************************************************
CONTENT OF COVER
*****************************************************************************/
$section->addText(
	htmlspecialchars(
		'ADATOK' .
		"\n" .
		"\n"
	),
	$titleFont
);

$section->addText(
	htmlspecialchars(
		'CÉG NEVE: ' . $company_name .
		"\n" .
		'VEZETŐ NEVE: ' . $manager_name .
		"\n" .
		'VEZETŐ CÍME: ' . $manager_address .
		"\n" .
		'TELEFON: ' . $company_phone .
		"\n" .
		'EMAIL: ' . $company_email .
		"\n" .
		'DÁTUM: ' . $starting_date . ' - ' . $ending_date .
		"\n" .
		'POSTANÉV: ' . $postal_name .
		"\n" .
		'POSTACÍM: ' . $postal_address .
		"\n" .
		"IRATŐRZŐ NEVE: " . $document_holder .
		"\n" .
		"IRATŐRZŐ CÍME: " .$document_holder_address
	),
	$baseFont
);

/*****************************************************************************
CREATING THE WORD DOCUMENT
*****************************************************************************/

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('cover.docx');

echo("success");

?>