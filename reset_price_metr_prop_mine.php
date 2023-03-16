<?
// there are two scripts which I woul rewrite in one
// but not now
?>
<?
// this one working correctly with offers in all sections without subsection
// not all offers from subsections are processed

$iblockID = 5;
$sectionID = [
	"LOGIC" => "OR",
	["SECTION_ID"=>1],
	["SECTION_ID"=>2],
	["SECTION_ID"=>3],
	["SECTION_ID"=>4],
	["SECTION_ID"=>30],
	["SECTION_ID"=>34],
	["SECTION_ID"=>37]
];
// 1 [19.20.22.23]	almost
// 2 [21]			~
// 3 [5.16.17.18]	almost
// 4 []				done
// 30 []			done
// 34 []			done
// 37 []			done
$ID = ["ID" => 2317];
$measureUnit = "м";

$arSelect = Array("ID", "NAME", "PROPERTY_PRICE", "PROPERTY_KV_METR", "IBLOCK_SECTION_ID");
$arFilter = Array("IBLOCK_ID"=>$iblockID, "ACTIVE"=>"Y", $sectionID, "INCLUDE_SUBSECTIONS"=>"Y", "!PROPERTY_PRICE"=>false);
// "INCLUDE_SUBSECTIONS"=>"Y" позволяет выбрать все элементы подгрупп инфоблоков, принадлежащих какой-либо группе
// "!PROPERTY_PRICE"=>false позволяет выбрать все элементы с заполненным свойством
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
echo '<pre>';
   var_dump($arFilter);
echo '</pre>';

while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$price = $arFields['PROPERTY_PRICE_VALUE'];
	$metrPrice = [
		"VALUE" => $price,
		"DESCRIPTION" => $measureUnit
	];

	if (!empty($price)) {
		CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array("KV_METR" => $metrPrice, "PRICE"=>false));
	}
	echo '<pre>';
		var_dump($arFields);
	echo '</pre>';
}
?>

<?
// this one working correctly with offers in all sections with subsection
// now I'm doubt about sections without subcections because I didn't try it in that categories

// 1 [19.20.22.23]	done
// 2 [21]			done
// 3 [5.16.17.18]	done
// 4 []				none
// 30 []			none
// 34 []			none
// 37 []			none
$ID = ["ID" => 2317];

$arSectionParentID = [1,2,3,4,30,34,37];
function getSectionsIDList($arSectionParentID) {
	$arSections = [];
	foreach ($arSectionParentID as $parentID) {
		$rsParentSection = CIBlockSection::GetByID($parentID);
		if ($arParentSection = $rsParentSection->GetNext()) {
			$arSectionFilter = [
				"ACTIVE"=>"Y",'IBLOCK_ID' => $arParentSection['IBLOCK_ID'],
				'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],
				'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],
				'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']
			];
			$dump[] = $arSectionFilter;
			$arSectionSelect = ["ID", "NAME", "IBLOCK_ID"];
			$rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),$arSectionFilter, true, $arSectionSelect);
			while ($arSect = $rsSect->GetNext()) {
				$arSections[] = $arSect['ID'];
			}
		} 
	}
	return $arSections = $dump;
}
echo '<pre>';
   var_dump(getSectionsIDList($arSectionParentID));
echo '</pre>';

$arSectionsID = getSectionsIDList($arSectionParentID);
function getElementsFilterSectionID($arSectionsID) {
	$filterSectionsID =  ["LOGIC" => "OR"];
	foreach ($arSectionsID as $sectID) {
		$filterSectionsID[] = ["SECTION_ID"=>$sectID];
	}
	return $filterSectionsID;
}

$iblockID = 5;
$measureUnit = "м";
$arSelect = Array("ID", "NAME", "PROPERTY_PRICE", "PROPERTY_KV_METR", "IBLOCK_SECTION_ID");
$arFilter = Array("IBLOCK_ID"=>$iblockID, "ACTIVE"=>"Y", getElementsFilterSectionID($arSectionsID), "!PROPERTY_PRICE"=>false);

$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$price = $arFields['PROPERTY_PRICE_VALUE'];
	$metrPrice = [
		"VALUE" => $price,
		"DESCRIPTION" => $measureUnit
	];
	// CIBlockElement::SetPropertyValuesEx($arFields["ID"], false, array("KV_METR" => $metrPrice, "PRICE"=>false));
}
?>