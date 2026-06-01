<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'position'   => $this->position,
            'is_primary' => $this->is_primary,
            'client_id'  => $this->client_id,
            'client'     => $this->whenLoaded('client', fn () => [
                'id'   => $this->client->id,
                'name' => $this->client->name,
            ]),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
