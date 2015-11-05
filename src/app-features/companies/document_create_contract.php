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
$company_phone = $request->company_phone;
$company_email = $request->company_email;
$service_provider = $request->service_provider;
$price_of_serv_num = $request->price_of_serv_num;
$price_of_serv_let = $request->price_of_serv_let;
$company_address = $request->company_address;
$company_register_id = $request->company_register_id;
$postal_service = $request->postal_service;
$postal_name = $request->postal_name;
$postal_address = $request->postal_address;
$manager_name = $request->manager_name;
$manager_status = $request->manager_status;
$manager_id = $request->manager_id;
$manager_mother_name = $request->manager_mother_name;
$manager_address = $request->manager_address;
$document_holder = $request->document_holder;
$document_holder_address = $request->document_holder_address;

switch ($service_provider) {
    case 'Zeller és Zeller Kft.':
        $contractor_name = 'Zeller és Zeller Kft.';
		$contractor_address = '1035 Budapest, Miklós u. 13. VIII/42.';
		$contractor_company_id = '01-09-936132';
        break;
    case 'World Top Sport Bt.':
        $contractor_name = 'World Top Sport Bt.';
		$contractor_address = '1035 Budapest, Miklós u. 13. VIII/42.';
		$contractor_company_id = '01-06-769699';
        break;
}

/*****************************************************************************
DATA OF THE CONTRACTOR
*****************************************************************************/

$contractor_manager_name = 'Zeller-Daczi Gábor';
$contractor_manager_status = 'ügyvezető';
$contractor_manager_id = '615912KA';
$contractor_manager_address = '1035 Budapest, Miklós u. 13. VIII/42.';
$contractor_manager_mother_name = 'Zeller Ildikó Anna';

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
    array('name' => 'Times New Roman', 'size' => 11, 'color' => '000000', 'bold' => false)
);

$titleFont = 'titleText';
$phpWord->addFontStyle(
    $titleFont,
    array('name' => 'Times New Roman', 'size' => 12, 'color' => '000000', 'bold' => true)
);

/*****************************************************************************
CONTENT OF PAGE 0
*****************************************************************************/
$section->addText(
	htmlspecialchars(
		'SZÉKHELYHASZNÁLATI HOZZÁJÁRULÁS' .
		"\n"
	),
	$titleFont
);

$section->addText(
	htmlspecialchars(
		'	Alulírott, Zeller-Daczi Gábor Zsolt (1035 Budapest Miklós utca 13. 8/42; adószám:71659549-1-41 Taj szám:039399102 adóazonosító 8426792553, sz. Kalocsa 1983.11.08. an. Zeller Ildikó Anna) mint tulajdonos hozzájárulok, hogy a tulajdonomban lévő ingatlant (1035 Budapest, Miklós utca 13. 8/42; hrsz: 18267/6/A/394) a(z) ' .
		$company_name . ' (' . $company_address . ', cégj.: ' . $company_register_id . ')' .
		' székhelyéül bejegyezze.' .
		"\n" .
		"\n" .
		'A ' . $company_name . ' az ingatlan felett tulajdoni, illetve egyéb vagyoni jogokat nem gyakorolhat.' .
		"\n" .
		"\n" .
		"\n" .
		'Budapest, ' . $starting_date .
		"\n" .
		"\n" .
		"\n" .
		"\n" .
		'Tulajdonos: ...................................' .
		"\n" .
		"\n" .
		"\n" .
		"\n" .
		'Tanú 1: ...................................' .
		"\n" .
		"\n" .
		"\n" .
		"\n" .
		'Tanú 2: ...................................'
	),
	$baseFont
);

$section->addPageBreak();

/*****************************************************************************
CONTENT OF PAGE 1
*****************************************************************************/

$section->addText(
	htmlspecialchars(
		'SZÉKHELYHASZNÁLATI SZERZŐDÉS' .
		"\n"
	),
	$titleFont
);

$section->addText(
	htmlspecialchars(
		'	amely létrejött egyrészről ' .
		$company_name . ' (' . $company_address . ', cégj.: ' . $company_register_id . ')' .
		', képviseli: ' .
		$manager_name . ', ' . $manager_status . ' (' . $manager_address . ', ig.sz.: ' . $manager_id . ', an.neve: ' . $manager_mother_name . ')' .
		', mint Bérlő, ' .
		"\n" .
		'	másrészről ' . $contractor_manager_name . ' (' . $contractor_manager_address . ', ig.sz.: ' . $contractor_manager_id . ', an.neve: ' . $contractor_manager_mother_name . ') ' .
		'mint Bérbeadó között a mai napon az alábbiak szerint:' .
		"\n"
	),
	$baseFont
);

$section->addText(
    htmlspecialchars(
        '	1. Bérbeadó bérbe adja, Bérlő bérbe veszi a Bérbeadó 1035 Budapest, Miklós utca 13. 8/42 ingatlant (hrsz: 18267/6/A/394) a jelen szerződésben foglalt feltételekkel.' .
        "\n" .
        '	2. Bérbeadó hozzájárul, hogy Bérlő székhelyként tüntesse fel az 1. pontban megjelölt ingatlant.' .
        "\n" .
        '	3. Bérlő tudomásul veszi, hogy az 1. pontban megjelölt cím más cégek székhelyeként is szerepel, illetve Bérbeadó további cégekkel is köthet bérleti szerződést a fenti ingatlanra vonatkozóan, a Bérlő jelen szerződésből fakadó jogainak korlátozása nélkül. ' .
        "\n" .
        '	4. Bérlő a bérleménybe sem ideiglenesen, sem állandó jelleggel, sem természetes, sem másik jogi személyt nem jelenthet be, azt tovább albérletbe/bérbe nem adhatja.' .
        "\n" .
        '	5. A Bérbeadó jogosult a szerződés meghosszabbításától indoklás nélkül elállni' .
        "\n" .
        '	6. A Bérlő súlyos szerződésszegése esetén a Bérbeadó felmondhatja a bérleti szerződést. Ebben az esetben a Bérbeadó a székhely törlését a cégnyilvántartásból kérheti.' .
        "\n" .
        '	7. Bérlő nem jogosult jelen szerződésből fakadó jogait harmadik fél részére átengedni.' .
        "\n" .
        '	8. Bérbeadó jogosult jelen szerződésből fakadó jogait harmadik fél részére átengedni, ha az nem sérti Bérlőt szerződéses jogainak gyakorlásában' .
        "\n" .
        '	9. A Bérlő mulasztásából, tevékenységéből, ügyviteléből, szerződésszegéséből eredő károkért, a Bérbeadó sem anyagi, sem erkölcsi felelősséget nem vállal.' .
        "\n" .
        '	10. A Bérbeadó biztosítja a cég részére, hogy a cég döntéseit, határozatait a székhelyen meghozhassa és ezzel a GT által előírt feltételeknek eleget tegyen.' .
        "\n" .
        '	11. Jelen szerződés kizárólag közös megegyezéssel módosítható. A szerződésmódosításban a felek tetszőlegesen módosíthatják a szerződés bármely rendelkezését.' .
        "\n" .
        '	12. Jelen szerződést egyoldalúan csak a Bérbeadó mondhatja fel a Bérlő súlyos szerződésszegése, vagy olyan körülmények bekövetkezése esetén, amelyek megakadályozzák a Bérbeadó kötelezettségeinek teljesítését.' .
        "\n" .
        '	13. A felek közösen megállapodhatnak, hogy a Bérbeadó személyes jelenlétre előre egyeztetett időpontban, munkaidőben díjazás ellenében munkahelyet és/vagy tárgyalót biztosít a Bérlőnek. Bérbeadó nem köteles olyan helyiséget biztosítani, amely kizárólag Bérlő által használható. Amennyiben a Bérlőnek a kért időpontban munkahely és/vagy tárgyaló nem biztosítható, akkor a Bérlő köteles más címen levő munkahelyről/tárgyalóról gondoskodni.' .
        "\n" .
        '	14. A cég vezetésében beállt változást a Bérlő 15 napon belül köteles bejelenteni a Bérbeadónak.' .
    	"\n" .
    	"\n"
    ),
    $baseFont
);

$section->addText(
	htmlspecialchars(
		'Budapest, ' . $starting_date .
		"\n" .
		"\n" .
		'Bérlő: ......................................' . '		' . 'Bérbeadó: ......................................' .
		"\n"
	),
	$baseFont
);

$section->addPageBreak();

/*****************************************************************************
CONTENT OF PAGE 2
*****************************************************************************/

$section->addText(
	htmlspecialchars(
		'SZÉKHELYHASZNÁLATI SZERZŐDÉS DÍJSZABÁS/FUTAMIDŐ' .
		"\n"
	),
	$titleFont
);

$section->addText(
	htmlspecialchars(
		'	1. A bérleti díj bruttó 0 Ft, azaz nulla forint. A Bérbeadó ingyen biztosítja a székhely címet a Bérlőnek.' .
		"\n" .
		'	2. A székhelyhasználati szerződés érvényességének feltétele, hogy a Bérlő megbízási szerződést kössön a Bérbeadó által meghatározott gazdasági társasággal a beérkező postai küldemények kezeléséről' .
		"\n" .
		'	3. A székhelyhasználati szerződés futamideje megegyezik a megbízási szerződés futamidejével.' .
		"\n" .
		'	4. Amennyiben a megbízási szerződés lejár/megszűnik (díjnemfizetés, szerződés bontás stb...), ezzel párhuzamosan a székhelyhasználati szerződés is megszűnik és a Bérlő köteles jelen melléklet 5. pontja alapján eljárni' .
		"\n" .
		'	5. A székhelyhasználati szerződés megszűnésekor Bérlő köteles székhelyét áthelyezni, és azt bejelenteni az illetékes hatóságoknak.' .
		"\n" .
		'	6. A szerződés megszűnésével a Bérbeadónak jogában áll a székhely megszűnést jelezni a hatóságok és Bérlő partnerei felé. ' .
		"\n" .
		'	7. Amennyiben a Bérlő nem tesz eleget a 5. pontban előírt kötelezettségének, a Bérbeadó jogosult a cégbíróságon eljárni és a cég törlését kezdeményezni.' .
		"\n" .
		"\n" .
		'	A szerződő felek jelen szerződést, mint akaratukkal mindenben megegyezőt írták alá. Kijelentik, hogy jogvita esetén a Fővárosi Bíróság illetékességét ismerik el. A szerződésben nem szabályozott kérdésekben a PTK. (és a lakások és nem lakó helyiségek bérbadásról szóló tv. Rendelkezési) az irányadó. A szerződéssel kapcsolatban szóbeli megállapodás nem született.' .
		"\n" .
		"\n"
	),
	$baseFont
);

$section->addText(
	htmlspecialchars(
		'Budapest, ' . $starting_date .
		"\n" .
		"\n" .
		'Bérlő: ......................................' . '		' . 'Bérbeadó: ......................................' .
		"\n"
	),
	$baseFont
);

$section->addPageBreak();

/*****************************************************************************
CONTENT OF PAGE 3
*****************************************************************************/

$section->addText(
	htmlspecialchars(
		'MEGBÍZÁSI SZERZŐDÉS' .
		"\n"
	),
	$titleFont
);

$section->addText(
	htmlspecialchars(
		'	amely létrejött egyrészről ' .
		$company_name . ' (' . $company_address . ', cégj.: ' . $company_register_id . ')' .
		', képviseli: ' .
		$manager_name . ', ' . $manager_status . ' (' . $manager_address . ', ig.sz.: ' . $manager_id . ', an.neve: ' . $manager_mother_name . ')' .
		', mint Bérlő, ' .
		"\n" .
		'	másrészről ' . $contractor_name . ' (' . $contractor_address . ', cégj: ' . $contractor_company_id . ') ' .
		', képviseli: ' . $contractor_manager_name . ' (' . $contractor_manager_address . ', ig.sz.: ' . $contractor_manager_id . ', an.neve: ' . $contractor_manager_mother_name . ')' .
		', mint Megbízott között a mai napon az alábbiak szerint:' .
		"\n"
	),
	$baseFont
);

$section->addText(
    htmlspecialchars(
        '	1. A Megbízott (2006. évi V. tv. 31. § (3) szerinti) kézbesítési megbízott feladatokat nem vállal.' .
        "\n" .
        '	2. A Megbízott a Megbízó részére, a 1035 Budapest, Miklós utca 13 8/42 címre érkező leveleket, küldeményeket, üzeneteket az 1. számú melléklet szerint továbbítja.' .
 		"\n" .
 		'	3. A Megbízott biztosítja, hogy a Megbízót, a 1035 Budapest, Miklós utca 13 8/42 címen felkereső személyeket és a hatóságokat a cégvezető mindenkori elérhetőségéről tájékoztatja, az üzeneteket mindkét fél részére továbbítja.' .
 		"\n" .
 		'	4. A Megbízott a Megbízó cégének nevét a 1035 Budapest, Miklós utca 13 8/42 tartozó ingatlan homlokzatán feltűnteti.' .
 		"\n" .
 		'	5. Megbízó nem jogosult jelen szerződésből fakadó jogait harmadik fél részére átengedni.' .
    	"\n" .
    	'	6. Megbízott jogosult jelen szerződésből fakadó jogait harmadik fél részére átengedni, ha az nem sérti Megbízót szerződéses jogainak gyakorlásában' .
 		"\n" .
 		'	7.	Jelen szerződés kizárólag közös megegyezéssel módosítható. A szerződésmódosításban a felek tetszőlegesen módosíthatják a szerződés bármely rendelkezését.' .
 		"\n" .
 		'	8. A Megbízott jogosult a szerződés meghosszabbításától indoklás nélkül elállni.' .
 		"\n" .
 		'	9. Jelen szerződést egyoldalúan csak a Megbízott mondhatja fel, kizárólag olyan körülmények bekövetkezése esetén, amelyek megakadályozzák a Megbízott kötelezettségeinek teljesítését.' .
 		"\n" .
 		'	10. Megbízó a névváltoztatás jogát fenntartja. A névváltozást 15 napon belül be kell jelentenie Megbízottnak.' .
 		"\n" .
 		'	11. A Megbízott cégiratokat nem őriz. A cég iratainak őrzési címe: ' . $document_holder . ', ' . $document_holder_address .
 		"\n" .
 		'	12. A Megbízó iratőrzési cím változás esetén köteles a változást a Megbízottnak bejelenteni' .
 		"\n" .
 		'	13. A Megbízó a cégbejegyzést követően 5 munkanapon belül leadja a következőket: postai meghatalmazás, társasági szerződés, cégbírósági végzés, aláírási címpéldány. A Megbízó tudomásul veszi, hogy ezen iratok leadásának hiányából származó károkért a Megbízott nem vállal felelősséget.' .
 		"\n" .
 		'	14. A cég vezetésében beállt változást a Megbízó 15 napon belül köteles bejelenteni. Ugyancsak köteles ekkor új aláírási címpéldányt, társasági szerződést, cégbírósági végzést és postai meghatalmazást biztosítani Megbízottónak. A Megbízó tudomásul veszi, hogy a változás bejelentésének hiányából és a fent említett iratok leadásának hiányából származó károkért a Megbízott nem vállal felelősséget.' .
 		"\n" .
 		'	15. Amennyiben a Megbízó a székhelyét a futamidő lejárta előtt a 1035 Budapest, Miklós utca 13 8/42 címről áthelyezi, a szerződés megszűnik, a befizetett megbízási/cégképviseleti díj nem jár vissza.' .
 		"\n" .
 		"\n"
   ),
    $baseFont
);

$section->addText(
	htmlspecialchars(
		'Budapest, ' . $starting_date .
		"\n" .
		"\n" .
		'Bérlő: ......................................' . '		' . 'Bérbeadó: ......................................' .
		"\n"
	),
	$baseFont
);

$section->addPageBreak();

/*****************************************************************************
CONTENT OF PAGE 4
*****************************************************************************/

$section->addText(
	htmlspecialchars(
		'MEGBÍZÁSI SZERZŐDÉS DÍJSZABÁS/FUTAMIDŐ' .
		"\n"
	),
	$titleFont
);

$section->addText(
	htmlspecialchars(
		'	1. A megbízási/cégképviseleti díj nettó ' . $price_of_serv_num . ' Ft, azaz ' . $price_of_serv_let . ' Forint. ' .
		'Ennek megfelelően : ' .
		"\n" .
		'		Az induló dátum: ' . $starting_date .
		"\n" .
		'		A következő díj esedékessége: ' . $ending_date .
		"\n" .
		'	2. A megbízási/cégképviseleti teljes összege a Megbízott számlája ellenében kerül kifizetésre a Megbízott részére.' .
		"\n" .
		'	3. A szerződő felek megállapodnak, hogy a szerződés hosszabbítása fél éves ütemezésben történik. Ennek megfelelően minden hosszabbításkor a Megbízó további  hat havi részletet egy összegben fizet meg a Megbízott részére.' .
		"\n" .
		'	4. 1 (azaz egy) havi megbízási/cégképviseleti díj összege a szerződés aláírásakor nettó ' . ($price_of_serv_num / 6) . ' Forint.' .
		"\n" .
		'	5. Amennyiben a Megbízó meg kívánja hosszabbítani a szerződését, legkésőbb a szerződés lejártát (az aktuális futamidő záró dátumát) követő 8 munkanapon belül készpénzben kifizeti, vagy átutalja a megbízási díj összegét a Megbízott bankszámlájára.' .
		"\n" .
		'	6. A folytatólagos díj megfizetéséről a Megbízó számlát állít ki, mely igazolja a szerződés meghosszabbítását.' .
		"\n" .
		'	7. A folytatólagos díj meg nem fizetése esetén a szerződés automatikusan megszűnik, tehát az érkező postai küldeményeket a Megbízott már nem köteles átvenni, továbbítani és megőrizni.' .
		"\n" .
		"\n" .
		'A szerződő felek jelen szerződést, mint akaratukkal mindenben megegyezőt írták alá. Kijelentik, hogy jogvita esetén a Fővárosi Bíróság illetékességét ismerik el. A szerződésben nem szabályozott kérdésekben a PTK. az irányadó. A szerződéssel kapcsolatban szóbeli megállapodás nem született.' .
		"\n" .
		"\n"
	),
	$baseFont
);

$section->addText(
	htmlspecialchars(
		'Budapest, ' . $starting_date .
		"\n" .
		"\n" .
		'Bérlő: ......................................' . '		' . 'Bérbeadó: ......................................' .
		"\n"
	),
	$baseFont
);

$section->addPageBreak();

/*****************************************************************************
CONTENT OF PAGE 5
*****************************************************************************/

$section->addText(
	htmlspecialchars(
		'MEGBÍZÁSI SZERZŐDÉS 1. SZÁMÚ MELLÉKLETE' .
		"\n" .
		'(KÜLDEMÉNYEK KEZELÉSI RENDJE)' . 
		"\n"
	),
	$titleFont
);

$section->addText(
	htmlspecialchars(
		'	Az átvett postai küldeményeket, üzeneteket a Megbízott a 1035 Budapest, Miklós utca 13. 8/42 alatti ingatlanban tárolja. A küldemények előre egyeztetett időpontban, munkaidő alatt vehetők át, illetve az alábbiak szerint kerülnek feldolgozásra:' .
		"\n" .
		"\n" .
		'	1. A Megbízó hozzájárul, hogy a Megbízott a székhelyre érkező postai küldeményeket az átvétel napján, vagy az átvételt követő munkanapon felbontsa, tartalmukat szkennelje és a Megbízó által megadott e-mail címre .pdf formátumban továbbítsa.' .
		"\n" .
		'	2. A Megbízó felelőssége, hogy az általa megadott e-mail cím a Megbízott által továbbított e-mail-ek fogadására alkalmas, valós cím legyen. E-mail cím változás esetén köteles a változást a Megbízónak bejelenteni.'
	),
	$baseFont
);

if($postal_service == 1){
	$section->addText(
		htmlspecialchars(
			'	3. A Megbízott a beérkezett küldeményeket havonta egyszer térítésmentesen továbbítja a Megbízó által megadott címre. A beérkezett küldemények egy borítékban, ajánlott küldeményként kerülnek elküldésre.'
		),
		$baseFont
	);
} else{
	$section->addText(
		htmlspecialchars(
			'	3. A beérkezett postai küldemények nem kerülnek a megadott továbbcímre postázásra. Ez kizárólag a Megbízó külön kérésére történik, ebben az esetben utánvéttel kerülnek feladásra a küldemények.'
		),
		$baseFont
	);
}

$section->addText(
	htmlspecialchars(
		'	4. A Megbízó felelőssége, hogy az általa megadott postacímen a Megbízott által továbbított leveleket átvegyék. Postacím változás esetén köteles a változást a Megbízott nak bejelenteni.' .
		"\n" .
		'	5. A Megbízó tudomásul veszi, hogy a beérkezett küldemények között olyan küldemények is lehetnek, melyek határidővel megjelölt hiánypótlást/személyes megjelenést/felszólítást/követelést tartalmaznak. Ezért a Megbízó felelőssége, hogy az általa megadott e-mail címen rendszeresen ellenőrizze a székhelyre érkező küldemények tartalmát és a benne foglalt kötelezettségének határidőre eleget tegyen.' .
		"\n" .
		'	6. Amennyiben bármely küldemény továbbítása a megadott e-mail címre sikertelen volt, a Megbízott SMS/telefonos értesítést küld a Megbízó részére és a továbbiakban addig nem köteles szkennelve továbbküldeni a leveleket, amíg a Bérlő a 2.-es pontban foglalt kötelezettségének nem tesz eleget.' .
		"\n" .
		'	7. Amennyiben bármely küldemény továbbítása a megadott postacímre sikertelen volt, a Megbízott SMS/telefonos értesítést küld a Megbízó részére és a továbbiakban nem köteles postázni a beérkezett küldeményeket, amíg a Bérlő a 4.-es pontban foglalt kötelezettségének nem tesz eleget.' .
		"\n" .
		"\n" .
		'A Megbízó az aláírás dátumakor az alábbi adatokat adta meg a küldemények rendeltetésszerű kezeléséhez: ' .
		"\n" .
		"\n" .
		'Telefon: ' . $company_phone .
		"\n" .
		'Email cím: ' . $company_email .
		"\n" .
		'Postacím: ' . $postal_name . ', ' . $postal_address .
		"\n"
	),
	$baseFont
);

$section->addText(
	htmlspecialchars(
		'Budapest, ' . $starting_date .
		"\n" .
		"\n" .
		'Bérlő: ......................................' . '		' . 'Bérbeadó: ......................................' .
		"\n"
	),
	$baseFont
);

/*****************************************************************************
CREATING THE WORD DOCUMENT
*****************************************************************************/

$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('contract.docx');

echo("success");

?>