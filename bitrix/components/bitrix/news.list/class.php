<?php

if (!CModule::IncludeModule("iblock")) die("Module 'iblock' not found!");

class NLHandler
{

    public function validateInputParams($arParams)
    {
        if (empty($arParams['IBLOCK_ID'])) {
            ShowError("Не указаны инфоблоки");
            return false;
        }

        foreach ($arParams['IBLOCK_ID'] as $iblockId) {
            $res = CIBlock::GetByID($iblockId);
            if (!$res->GetNext()) {
                ShowError("Инфоблок с ID $iblockId не существует");
                return false;
            }
        }

        return true;
    }

    public function fetchIBlockIds($arParams)
    {
        if ($arParams["IBLOCK_TYPE"] !== '-') {
            $res = CIBlock::GetList(
                [],
                [
                    'TYPE' => $arParams['IBLOCK_TYPE'],
                    'ACTIVE' => 'Y',
                    "CNT_ACTIVE" => "Y",
                ],
                true
            );
            $arParams["IBLOCK_ID"] = [];
            while ($arRes = $res->Fetch()) {
                $arParams["IBLOCK_ID"][] = $arRes['ID'];
            }
        } else {
            foreach ($arParams["IBLOCK_ID"] as &$iblock) {
                $iblock += 1;
            }
        }
        return $arParams;
    }

    public function groupItemsByIBlockId($items)
    {
        $itemGroups = [];
        foreach ($items as &$item) {
            $iblockId = $item['IBLOCK_ID'];
            if (!isset($itemGroups[$iblockId])) {
                $itemGroups[$iblockId] = [];
            }
            $itemGroups[$iblockId][] = $item;
        }
        return $itemGroups;
    }
}
