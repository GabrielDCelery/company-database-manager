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
$requests = json_decode($postdata);

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
    array('name' => 'Times New Roman', 'size' => 10, 'color' => '000000', 'bold' => false)
);

$titleFont = 'titleText';
$phpWord->addFontStyle(
    $titleFont,
    array('name' => 'Times New Roman', 'size' => 12, 'color' => '000000', 'bold' => true)
);

/*****************************************************************************
CONTENT
*****************************************************************************/

$id_list = $request->id;

$section->addText(
	htmlspecialchars(
		'ÁTVÉTELI ELISMERVÉNY' .
		"\n" .
		'LEVELEK' .
		"\n"
	),
	$titleFont
);

foreach($requests as $request){
	$company_name = $request->company_name;
	$sender_name = $request->sender_name;
	$sender_address = $request->sender_address;
	$receiving_date = $request->receiving_date;

	if(!is_null($receiving_date)){
		$receiving_date = strtotime($receiving_date);
		$receiving_date = date('Y-m-d', $receiving_date);
	}

	$section->addText(
		htmlspecialchars(
			$company_name . ' - ' . $sender_name . ', ' . $sender_address . ' / ' . $receiving_date
		),
		$baseFont
	);
}

$today = date("Y-m-d"); 

$section->addText(
	htmlspecialchars(
		"\n" .
		'A fent felsorolt küldemény(ek) és hivatalos irat(ok) épségéről és rendeltetésszerű tárolásáról/kezeléséről személyesen meggyőződtem és a mai nappal átvettem.' .
		"\n" .
		"\n" .
		'Budapest, ' . $today .
		"\n" .
		"\n" .
		"\n" .
		'Nyomtatott név: ...............................................' .
		"\n" .
		"\n" .
		"\n" .
		'Sz.ig. szám: ...............................................' .
		"\n" .
		"\n" .
		"\n" .
		'Állandó lakcím: ...............................................' .
		"\n" .
		"\n" .
		"\n" .
		'Aláírás: ...............................................'
	),
	$baseFont
);

/*****************************************************************************
CREATING THE WORD DOCUMENT
*****************************************************************************/

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('receit.docx');

?>