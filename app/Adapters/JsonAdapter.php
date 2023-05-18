<?php

namespace App\Adapters;

use App\Helpers\JsonAdapterHelper;

class JsonAdapter 
{
    public static function transformData($json, $jsonMaps)
    {

        $outputJson = [];

        foreach($jsonMaps as $map => $control) {
            if (JsonAdapterHelper::isJsonRoot($map)) {
                if (JsonAdapterHelper::isDestinyArray($control)) {
                    $outputJson[$control['destinyArrayKey']] = JsonAdapter::exchangeValueReturnArray($json, $control['map']);
                } else {
                    $outputJson[] = JsonAdapter::exchangeValue($json, $control['map']);
                }
            } else {
                if (JsonAdapterHelper::isOrigemArray($control)) {
                    $outputJson[$control['destinyArrayKey']] = JsonAdapter::getJsonNestedArray($json, $control);
                } else {
                    if (JsonAdapterHelper::isDestinyArray($control)) {
                        $outputJson[$control['destinyArrayKey']] = JsonAdapter::exchangeValueReturnArray($json, $control['map']);
                    } else {
                        $outputJson[$map] = JsonAdapter::exchangeValue($json, $control['map']);
                    }
                }
            }
        }

        return $outputJson;
    }    

    private static function exchangeValue($data, $keyMapping)
    {
        $outputJson = [];

        foreach ($keyMapping as $inputKey => $outputKey) {

            if(is_array($outputKey)){
                $key = $outputKey['destino'];
            } else {
                $key = $outputKey;
            }

            $value = JsonAdapter::getValueFromKey($data, $inputKey);
            
            /*
             *  Tratar Conversao de dados a partir do $value retornado
             */           
            JsonAdapterHelper::applyDataMapping($value, $outputKey);
            
            JsonAdapter::setValueFromKey($outputJson, $key, $value);

        }

        return $outputJson;
    }

    private static function exchangeValueReturnArray($data, $keyMapping)
    {
        return array(JsonAdapter::exchangeValue($data, $keyMapping));
    }

    private static function getJsonNestedArray($json, $control)
    {
        $outputData = [];
        foreach ($json[$control['orignArrayKey']] as $item) {
            $outputData[] = JsonAdapter::exchangeValue($item, $control['map']);
        }
        return $outputData;
    }

    private static function getValueFromKey($data, $key)
    {

        $keys = explode('.', $key);

        $currentData = $data;

        foreach ($keys as $keyPart) {

            if (is_array($currentData) && isset($currentData[$keyPart])) {
                $currentData = $currentData[$keyPart];
            } else {
                return null;
            }
        }

        return $currentData;
    }

    private static function setValueFromKey(&$data, $key, $value)
    {
        $keys = explode('.', $key);

        $currentData = &$data;

        foreach ($keys as $keyPart) {
            if (!isset($currentData[$keyPart])) {
                $currentData[$keyPart] = [];
            }
            $currentData = &$currentData[$keyPart];
        }

        $currentData = $value;
    }
}