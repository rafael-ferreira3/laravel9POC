<?php

namespace App\Helpers;

class JsonAdapterHelper
{
    public static function isJsonRoot($key) {
        return $key === '/';
    }

    public static function isOrigemArray($control) {
        return isset($control['orignArrayKey']);
    }

    public static function isDestinyArray($control) {
        return isset($control['destinyArrayKey']);
    }

    public static function applyDataMapping(&$originalValue, $map) {
        $newValue = &$originalValue;
        if (isset($map['dataMap'])) {
            foreach($map['dataMap'] as $datamap) {
                if ($datamap['tipo'] == 'replace') {
                    foreach($datamap['dados'] as $replace) {
                        $newValue = str_replace($replace['search'], $replace['replace'],$newValue);                        
                    }                                   
                }
            }
        }
        return $newValue;
    }

}