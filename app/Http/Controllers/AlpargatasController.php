<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Adapters\JsonAdapter;
use App\Mock\JsonAlpargatasMock;

class AlpargatasController extends Controller
{

    public function integrarPedido(Request $request) {
        $json = json_decode($request->getContent(), true);

        /*
            Recuperar o mapeamento do json do banco de dados
        */
        $completeMap = JsonAlpargatasMock::getAlpargatasPedidoMap();

        $formattedJson = JsonAdapter::transformData($json, $completeMap);

        return $formattedJson;
    }

}
