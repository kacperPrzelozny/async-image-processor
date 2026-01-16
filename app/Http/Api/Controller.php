<?php

namespace App\Http\Api;

use Illuminate\Http\JsonResponse;

class Controller extends \App\Http\Controllers\Controller
{
    protected function responseSuccess($data = [], $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
        ], $code);
    }

    protected function responseError($message, $errors = [], $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
