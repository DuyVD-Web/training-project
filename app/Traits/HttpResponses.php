<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HttpResponses
{
    protected function responseSuccess($data = [], $code = 200): JsonResponse
    {
        return response()->json([
            "status" => true,
            "data" => $data,
        ], $code);
    }

    protected function responseError($message, $errors = [], $code = 500): JsonResponse
    {
        return response()->json([
            "status" => false,
            'message' => $message,
            "errors" => $errors,
            "code" => $code
        ], $code);
    }
}
