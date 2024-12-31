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

    public function sendPaginateResponse ($model, $data = [], $code = 200): JsonResponse
    {
        $data ['meta'] =  [
            'current_page' => $model->currentPage(),
            'last_page' => $model->lastPage(),
            'per_page' => $model->perPage(),
            'total' => $model->total(),
            'from' => $model->firstItem(),
            'to' => $model->lastItem(),
            'links' => [
                'first' => $model->url(1),
                'last' => $model->url($model->lastPage()),
                'prev' => $model->previousPageUrl(),
                'next' => $model->nextPageUrl(),
            ]
        ];
        return response()->json([
            "status" => true,
            "data" => $data
        ], $code);
    }
}
