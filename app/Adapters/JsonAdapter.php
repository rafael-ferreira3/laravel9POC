<?php

namespace App\Adapters;

class JsonAdapter 
{
    public static function transformData($json, $jsonMaps)
    {

        $outputJson = [];

        foreach($jsonMaps as $map => $control) {
            if ($map === '/') {
                if (isset($control['destinyArrayKey'])) {
                    $outputJson[$control['destinyArrayKey']] = array(JsonAdapter::exchangeValue($json, $control['map']));
                } else {
                    $outputJson[] = JsonAdapter::exchangeValue($json, $control['map']);
                }
            } else {
                if (isset($control['orignArrayKey'])) {
                    $outputData = [];
                    foreach ($json[$control['orignArrayKey']] as $item) {
                        $outputData[] = JsonAdapter::exchangeValue($item, $control['map']);
                    }
                    $outputJson[$control['destinyArrayKey']] = $outputData;
                } else {
                    if (isset($control['destinyArrayKey'])) {
                        $outputJson[$control['destinyArrayKey']] = array(JsonAdapter::exchangeValue($json, $control['map']));
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
        $outputData = [];

        foreach ($keyMapping as $inputKey => $outputKey) {

            if(is_array($outputKey)){
                $key = $outputKey['destino'];
            } else {
                $key = $outputKey;
            }

            $inputValue = JsonAdapter::getValueFromKey($data, $inputKey);
            
            /*
             *  Tratar Conversao de dados a partir do $inputValue
             */            

            if (isset($outputKey['dataMap'])) {
                foreach($outputKey['dataMap'] as $datamap) {
                    if ($datamap['tipo'] == 'replace') {
                        foreach($datamap['dados'] as $replace) {
                            $inputValue = str_replace($replace['search'], $replace['replace'],$inputValue);                        
                        }                                   
                    }
                }
            }

            JsonAdapter::setValueFromKey($outputData, $key, $inputValue);

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