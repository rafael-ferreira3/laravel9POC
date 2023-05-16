<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiRequestTeste;
use App\Models\NodeModel;
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

    public function testeAdapter(ApiRequestTeste $request) {
       //return $request->all();
    }

    public function convertJson(Request $request) {
        $input=$request->all();

        $user = $input['results'][0];

        $rules = $this->getRules();
        
        $newUser = [];

        foreach($rules as $rule) {
            $value = $this->getArrayValue($user, $rule->nodeOrigem);
            $this->setArrayValue($newUser, $rule->nodeDestino, $value);
        }

        return $newUser;

    }

    public function getArrayValue($array, $keysString) {

        $keys = explode('.', $keysString);

        $a = &$array;
        $k = '';
        while (count($keys) > 0) {
            $k = array_shift($keys);
            $a = &$a[$k];
        }
        return $a;
    }

    public function setArrayValue(&$array, $keysString, $value) {

        $keys = explode('.', $keysString);

        $a = &$array;
        $k = '';
        while (count($keys) > 0) {
            $k = array_shift($keys);
            $a = &$a[$k];
        }
        
        $a = $value;
    }

    public function getRules() {

        $rules = [];

        $node = new NodeModel();
        $node->nodeOrigem = 'name.first';
        $node->nodeDestino = 'nome';

        $rules[] = $node;

        $node = new NodeModel();
        $node->nodeOrigem = 'name.last';
        $node->nodeDestino = 'sobrenome';

        $rules[] = $node;

        $node = new NodeModel();
        $node->nodeOrigem = 'email';
        $node->nodeDestino = 'email';

        $rules[] = $node;

        $node = new NodeModel();
        $node->nodeOrigem = 'location.state';
        $node->nodeDestino = 'estado';

        $rules[] = $node;

        $node = new NodeModel();
        $node->nodeOrigem = 'location.city';
        $node->nodeDestino = 'cidade';

        $rules[] = $node;

        $node = new NodeModel();
        $node->nodeOrigem = 'location.street.name';
        $node->nodeDestino = 'rua';

        $rules[] = $node;

        $node = new NodeModel();
        $node->nodeOrigem = 'location.street.number';
        $node->nodeDestino = 'numero';

        $rules[] = $node;

        return $rules;
    }

}
