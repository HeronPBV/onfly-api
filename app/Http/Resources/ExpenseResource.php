<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this);
        return [
            'usuario' => $this->formatUser($request),
            'descricao' => $this->description,
            'valor' => $this->formatValueOutput($this->value, $request),
            'data' => formatDate($this->date, $request),
            'tempo_decorrido' => $this->getTimeElapsed(),
        ];
    }

    private function formatUser(Request $request): array
    {
        return [
            'nome' => $this->user->name,
            'email' => $this->user->email,
        ];
    }

    private function formatValueOutput(float $value, Request $request): ?string
    {
        return formatValue($value);
    }

    private function getTimeElapsed(): string
    {
        return Carbon::parse($this->date)->diffForHumans();
    }

}
