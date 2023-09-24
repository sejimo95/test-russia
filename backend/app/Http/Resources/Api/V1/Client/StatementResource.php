<?php

namespace App\Http\Resources\Api\V1\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class StatementResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'date' => $this->date,
            'created_at' => $this->generateDate($this->created_at),
            'updated_at' => $this->generateDate($this->updated_at)
        ];
    }

    private function generateDate($value) {
        return date('Y-m-d H:i:s', strtotime($value));
    }
}
