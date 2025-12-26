<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class GatewayException extends Exception
{
    public function report()
    {
        Log::error($this->getMessage());
    }


    public function render($request): JsonResponse
    {
        return response()->json([
            'error' => 'An error occurred with the payment gateway'
        ], 500);
    }
}
