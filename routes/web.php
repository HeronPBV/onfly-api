<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $data = [

        "API Onfly"=> "API RESTful, para gerenciamento de despesas e usuários.",
        "Instrução"=> "Acesse a documentação para descobrir os endpoints disponíveis",
        'Documentação' => DOC

    ];
    return response()->json($data, 200);

});
