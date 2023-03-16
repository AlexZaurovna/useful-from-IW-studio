<?
// filling the direct price property according with another without rushial letters and spaces

$iblockID = 5;
// $ID = 2305; simple price
// $ID = 819;  price with "от"
// $ID = 5837; empty field

$arSelect = Array("ID", "NAME", "PROPERTY_PRICE", 'PROPERTY_DIRECT_PRICE');
$arFilter = Array("IBLOCK_ID"=>$iblockID, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);

while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$price = $arFields['PROPERTY_PRICE_VALUE'];
	if (!empty($price)) {
		// get string consisting of number
		$directPrice = mb_ereg_replace('[^[:digit:]]', '', $price);
		CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array('DIRECT_PRICE' => $directPrice));
	}
}