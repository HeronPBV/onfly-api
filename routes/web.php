<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    $data = [

        'API Onfly' => 'Para conhecer os endpoints disponíveis, consulte a documentação',
        'Documentação' => 'https://www.heronboares.com.br'

    ];
    return response()->json($data, 200);

});
