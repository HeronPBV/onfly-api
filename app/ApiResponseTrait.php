<?php

namespace App;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    protected function successResponse(string $message, $data = null, int $statusCode = 200): JsonResponse
    {
        $response = ['Sucesso' => $message];
        if ($data) {
            $response['data'] = $data;
        }
        return response()->json($response, $statusCode);
    }

    protected function errorResponse(string $message, int $statusCode): JsonResponse
    {
        return response()->json(['Erro' => $message], $statusCode);
    }
}
