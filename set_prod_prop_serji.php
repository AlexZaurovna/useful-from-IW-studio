<?
// fill one property according to another 

$brandList = Array(
	
	'Dr.Koffer New York'=>'СОЕДИНЕННЫЕ ШТАТЫ',
	'Zippo' => 'СОЕДИНЕННЫЕ ШТАТЫ',
	'Tri Slona' => 'ЯПОНИЯ',
	'Victorinox' => 'ШВЕЙЦАРИЯ',
	'Tony Perotti' => 'ИТАЛИЯ',
	'Wenger' => 'ШВЕЙЦАРИЯ',
	'S.Quire' => 'ИТАЛИЯ',
	'Pierre Cardin' => 'ФРАНЦИЯ',
	'Parker' => 'ВЕЛИКОБРИТАНИЯ',
	'Joy Bells' => 'ЮЖНАЯ КОРЕЯ',
	'Waterman' => 'ФРАНЦИЯ',
	'Навигатор' => 'РОССИЯ',
	'Sevaro Elit' => 'ИТАЛИЯ',
	'Tosoco' => 'КИТАЙ',
	'Оникс' => 'ПАКИСТАН',
	'Solingen' => 'ГЕРМАНИЯ',
	'Свечи' => 'РОССИЯ',
	'СомС' => 'РОССИЯ',
	'Орбита' => 'РОССИЯ',
	'Caterpillar' => 'СОЕДИНЕННЫЕ ШТАТЫ',
	'Fulton' => 'ВЕЛИКОБРИТАНИЯ',
	'Янтарь+Бронза' => 'РОССИЯ',
	'SwissGear' => 'ШВЕЙЦАРИЯ',
	'Mario Pilli' => 'ИТАЛИЯ',
	'Klondike' => 'ГЕРМАНИЯ',
	'Jardin D`Ete' => 'ФРАНЦИЯ',
	'IT' => 'ВЕЛИКОБРИТАНИЯ',
	'Henry Backer (London)' => 'ВЕЛИКОБРИТАНИЯ',
	'Giorgio Ferretti' => 'ИТАЛИЯ',
	'GD маникюрные наборы' => 'ГЕРМАНИЯ',
	'Dor.Flinger' => 'ГЕРМАНИЯ',
	'Dierhoff' => 'ГЕРМАНИЯ',
	'MONDIAL' => 'ИТАЛИЯ',
	'HAUSER' => 'ГЕРМАНИЯ',
	'Bruno Perri' => 'ИТАЛИЯ',
	'SV'=>'КИТАЙ'
);

$arSelect = Array("ID", "NAME", "PROPERTY_BRAND", 'PROPERTY_BRAND.NAME', "PROPERTY_PROISKHOZHDENIE");
$arFilter = Array("IBLOCK_ID"=>21, "ACTIVE"=>"Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
$usluga = "Услуга";

while($ob = $res->GetNextElement())
{	
	$arFields = $ob->GetFields();

		$brandName = $brandList[$arFields['PROPERTY_BRAND_NAME']];
		if (empty($brandName) && stripos($usluga, $arFields['NAME'])=== false) {
			$brandName = 'КИТАЙ';
		}


	$property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>21, "CODE"=>"PROISKHOZHDENIE", 'VALUE'=>$brandName));
	if($enum_fields = $property_enums->GetNext())
	{
		// echo $enum_fields["ID"]." - ".$enum_fields["VALUE"]."<br>";
	
	CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array("PROISKHOZHDENIE" => $enum_fields["ID"]));
	}

	// echo '<pre>';
	// var_dump($arFields);
	// echo '</pre>';

}

// cron
/public_html/test2.php&