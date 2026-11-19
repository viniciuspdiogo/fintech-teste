<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountsTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => $this->type,
            'type_label' => $this->type->label(),
            'amount' => $this->amount,
            'balance_momentary' => $this->balance_momentary,
            'created_at' => $this->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i')
        ];
    }
}
