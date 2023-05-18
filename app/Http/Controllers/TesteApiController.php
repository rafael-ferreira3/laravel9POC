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

    public function convertJsonPedido(Request $request) {
        $pedidos=json_decode($request->getContent(), true);

        $newPedidos = [];

        $mapping = array(
            'pedidos' => 'ordemSeparacao',
            'pedidos.notafiscal' => 'numeroOrdem',
            'itens' => 'pecas',
            'produtos' => 'prod',
            'qtde' => 'qtd'

        );
        
        $originalArray = $pedidos;
        $mappingArray = array(
            'pedidos' => 'ordemSeparacao',
            'pedidos.*.notafiscal' => 'ordemSeparacao.*.numeroOrdem',
            'pedidos.*.itens' => 'ordemSeparacao.*.produtos',
            'pedidos.*.itens.*.produto' => 'ordemSeparacao.*.produtos.*.prod',
            'pedidos.*.itens.*.qtde' => 'ordemSeparacao.*.produtos.*.qtd'
        );

        $json = '
        {
            "name": "John Doe",
            "age": 30,
            "address": {
                "street": "123 Main St",
                "city": "New York"
            },
            "hobbies": [
                {
                    "name": "reading",
                    "type": "indoor"
                },
                {
                    "name": "painting",
                    "type": "creative"
                },
                {
                    "name": "gaming",
                    "type": "indoor"
                }
            ]
        }
        ';
        
        $mappingArray = array(
            'pedidos' => 'ordemSeparacao',
            'pedidos.*.notafiscal' => 'ordemSeparacao.*.numeroOrdem',
            'pedidos.*.itens' => 'ordemSeparacao.*.produtos',
            'pedidos.*.itens.*.produto' => 'ordemSeparacao.*.produtos.*.prod',
            'pedidos.*.itens.*.qtde' => 'ordemSeparacao.*.produtos.*.qtd'
        );

        $mappingArrayT = array(
            'pedidos.*.notafiscal' => 'ordemSeparacao.*.numeroOrdem'
        );

        // Key mapping array
        $keyMapping = array(
            #"name" => "nome",
            #"age" => "idade",
            #"address.street" => "location.endereco.rua",
            #"address.city" => "location.endereco.cidade",
            #"hobbies.*.name" => "interesses.*.nome",
            #"hobbies.*.type" => "interesses.*.tipo"
            "hobbies.*" => [
                "name" => "nome",
                "type" => "tipo"
            ]
        );

        #$neww = $this->transformJson($json, $mappingArray);

        #$neww = $this->transformJson2($pedidos, $mappingArrayT);

        $neww = $this->transformData($pedidos, $keyMapping);

        return $neww;
    }

    private function transformData($data, $keyMapping)
    {
        $outputData = [];

        foreach ($keyMapping as $inputKey => $outputKey) {
            if (str_contains($inputKey, '*')) {
                $newKey = str_replace('.*', '', $inputKey);
                dump($inputKey, $newKey);
                $inputValue = $this->getValueFromArrayKey($data[$newKey], $outputKey);
            } else {
                $inputValue = $this->getValueFromKey($data, $inputKey);
            }
            
            #dump($inputValue);
            $this->setValueFromKey($outputData, $outputKey, $inputValue);
        }

        return $outputData;
    }

    /**
     * Get value from key in nested data array
     *
     * @param array $data The input data
     * @param string $key The input key
     *
     * @return mixed|null The value from the key, or null if not found
     */
    private function getValueFromKey($data, $key)
    {

        if (str_contains($key, '*')) {
            dd('ERRO!');
        }

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

    private function getValueFromArrayKey($data, $key){
        $keys = $key;

        $currentData = $data;

        $values = [];

        dd($data, $key);

        foreach ($data as $json) {
                       
        }
    }

    /**
     * Set value from key in nested data array
     *
     * @param array $data The output data
     * @param string $key The output key
     * @param mixed $value The value to set
     */
    private function setValueFromKey(&$data, $key, $value)
{
    $keys = explode('.', $key);

    $currentData = &$data;

    foreach ($keys as $keyPart) {
        if ($keyPart === '*') {
            if (!is_array($currentData)) {
                $currentData = [];
            }

            $nextKeys = array_slice($keys, 1);
            if (empty($nextKeys)) {
                $currentData[] = $value;
            } else {
                foreach ($currentData as &$item) {
                    $this->setValueFromKey($item, implode('.', $nextKeys), $value);
                }
            }
        } else {
            if (!isset($currentData[$keyPart])) {
                $currentData[$keyPart] = [];
            }

            $currentData = &$currentData[$keyPart];
        }
    }

    $currentData = $value;
}

    ###############################################################

    public function changeValue($oldArray, &$newArray, $rule) {
        $value = $this->getArrayValue($oldArray, $rule->nodeOrigem);

        $this->setArrayValue($newArray, $rule->nodeDestino, $value);
    }

    public function getArrayValueJson($array, $keysString) {

        $keys = explode('.', $keysString);

        $a = &$array;
        while (count($keys) > 0) {
            $k = array_shift($keys);
            if($k != '[]') {
                $a = &$a[$k];
            }
            
        }
        return $a;
    }

    public function setArrayValueJson(&$array, $keysString, $value) {

        $keys = explode('.', $keysString);

        $a = &$array;
        $k='';
        while (count($keys) > 0) {
            $old = $k;
            $k = array_shift($keys);
            if($k == '[]') {
                if(!is_array($a)){
                    $a = [];
                }
            } else {
                $a = &$a[$k];
            }
        }
        
        $a = $value;
    }

    public function setArrayValue(&$array, $keysString, $value) {

        $keys = explode('.', $keysString);

        $a = &$array;
        while (count($keys) > 0) {
            $k = array_shift($keys);
            $a = &$a[$k];
        }
        
        $a = $value;
    }

    public function getArrayValue($array, $keysString) {

        $keys = explode('.', $keysString);

        $a = &$array;
        while (count($keys) > 0) {
            $k = array_shift($keys);
            $a = &$a[$k];
            
        }
        return $a;
    }

    public function getRulesPedido() {

        $rules = [];

        $node = new NodeModel();
        $node->nodeOrigem = 'pedidos';
        $node->nodeDestino = 'ordemSeparacao';

        $rules[] = $node;

        $node = new NodeModel();
        $node->nodeOrigem = 'pedidos.notafiscal';
        $node->nodeDestino = 'ordemSeparacao.NumeroOrdem';

        $rules[] = $node;

        $node = new NodeModel();
        $node->nodeOrigem = 'pedidos.itens';
        $node->nodeDestino = 'ordemSeparacao.itens';

        $rules[] = $node;

        $node = new NodeModel();
        $node->nodeOrigem = 'pedidos.itens.produto';
        $node->nodeDestino = 'ordemSeparacao.itens.prod';

        $rules[] = $node;

        $node = new NodeModel();
        $node->nodeOrigem = 'pedidos.itens.qtde';
        $node->nodeDestino = 'ordemSeparacao.itens.qtd';

        $rules[] = $node;

        return $rules;
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
