<?php

class ViewHelper {

    static function getSelectHtml($itemId, $itemName, $itemList, $searchId ){
        $selectHtml = '';
        for($i = 0; $i < count($itemList); $i++){
            $item = $itemList[$i];
            if ( $item[$itemId] == $searchId ) {
                $selectHtml .= "<option value='" . $item[$itemId] . "' selected>" . $item[$itemName] . "</option>";
            } else{
                $selectHtml .= "<option value='" . $item[$itemId] . "'>" . $item[$itemName] . "</option>";
            }
        }
        $selectHtml .= "</select>";

        return $selectHtml;

    }

    static function getCountTdStyle($wkAvgCount, $avgCount){

        $tdStyle = '';
        if($wkAvgCount == -1){
            $tdStyle = "color:black;background-color:white;text-align:right";
        }else if($avgCount > $wkAvgCount){
            $tdStyle = "color:blue;background-color:white;text-align:right";
        }else if($avgCount < $wkAvgCount){
            $tdStyle = "color:red;background-color:white;text-align:right";
        }else{
            $tdStyle = "color:black;background-color:white;text-align:right";
        }

        return $tdStyle;

    }


}

