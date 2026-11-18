<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class InsufficientFundsException extends Exception
{
    public function __construct()
    {
        parent::__construct('Saldo insuficiente.');
    }

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->getMessage()], 422);
    }
}
