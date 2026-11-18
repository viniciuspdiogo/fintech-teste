<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class AccountNotFoundException extends Exception
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        parent::__construct('Conta não encontrada.');
    }

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->getMessage()], 422);
    }
}
