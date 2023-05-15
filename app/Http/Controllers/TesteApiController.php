<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleXMLElement;

class TesteApiController extends Controller
{
    public function testeApi(Request $request) {
        $xml = new SimpleXMLElement('<root/>');
        //dd($request->all());

        //return $request->all();

        $this->arrayToXml($request->all(), $xml);

        return $xml->asXML();
    }

    function arrayToXml($array, &$xml){
        foreach ($array as $key => $value) {
            if(is_int($key)){
                $key = "e";
            }
            if(is_array($value)){
                $label = $xml->addChild($key);
                $this->arrayToXml($value, $label);
            }
            else {
                $xml->addChild($key, htmlspecialchars($value));
            }
        }
    }

}
