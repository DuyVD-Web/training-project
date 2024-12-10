<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait HttpResponses
{
    protected function responseSuccess($data = [], $status = 200): JsonResponse
    {
        return response()->json($data, $status);
    }

    protected function responseError($data = [], $status = 400): JsonResponse
    {
        return response()->json($data, $status);
    }
}
