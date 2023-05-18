<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Adapters\JsonAdapter;

class AlpargatasController extends Controller
{

    public function cadastrar(Request $request) {
        $json = json_decode($request->getContent(), true);

        $completeMap = [
            '/' => [ #root from json
                'map' => [
                    'cnpjDepositante' => 'cliente_cnpj',
                    'numeroPedido' => 'pedido_venda',
                    'dataEmissao' => 'data_entrega',
                    #'?' => 'prefixo',
                    'codigoClassificacaoPedido' => 'codigoClassificacaoPedido',
                    'transportadora.inscricaoEstadual' => 'transp_ie',
                    'transportadora.documento' => 'transp_cnpj',
                    'transportadora.nome' => 'transp_nome',
                    'totalItens' => 'qtde_itens',
                    #'?' => 'sequencia_entrega',
                    #'?' => 'cod_rota'
                ],
                'destinyArrayKey'=>'pedidos'
            ], 
            'notas_fiscal' => [
                'map' => [
                    'numeroDocumentoFiscal' => 'nota_fiscal',
                    #'?' => 'serie',
                    'valorTotalDocumento' => 'valor_total',
                    #?' => 'protNFe',
                    #'?' => 'cifFob',
                    #'' => 'chave_acesso',
                    #'' => 'danfe'
                ]
            ],
            'endereco_entregas' => [
                'map' => [
                    'destinatario.nome' => 'nome',
                    'destinatario.documento' => [
                        'destino'=>'cnpj',
                        'dataMap' => [[
                            'tipo' => 'replace',
                            'dados' => [
                                [ 'search' => '.', 'replace' => ''],
                                [ 'search' => '/', 'replace' => ''],
                                [ 'search' => '-', 'replace' => '']
                            ]
                        ]]
                    ],
                    'destinatario.inscricaoEstadual' => 'ie',
                    'destinatario.endereco.cep' => 'cep',
                    'destinatario.endereco.logradouro' => 'endereco',
                    'destinatario.endereco.bairro' => 'bairro',
                    'destinatario.endereco.cidade' => 'cidade',
                    'destinatario.endereco.estado' => 'estado',
                    'destinatario.telefone' => 'telefone',

                    'destinatario.tipo' => [
                        'destino'=>'fis_jur',
                        'dataMap' => [[
                            'tipo' => 'replace',
                            'dados' => [
                                [ 'search' => 'PESSOA_JURIDICA', 'replace' => 'J']
                            ]
                        ]]
                    ],                    
                ]
            ],
            'itens' => [
                'map' => [
                    'barra' => 'pn',
                    'quantidade' => 'qtde',
                    'valorTotal' => 'valor_total',
                    'valorUnitario' => 'valor_unitario'
                ],
                'orignArrayKey' => 'itens',
                'destinyArrayKey'=>'itens'
            ]
        ];

        $formattedJson = JsonAdapter::transformData($json, $completeMap);

        return $formattedJson;
    }

}
