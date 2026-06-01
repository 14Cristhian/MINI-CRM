<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'email'           => $this->email,
            'phone'           => $this->phone,
            'company'         => $this->company,
            'status'          => $this->status,
            'notes'           => $this->notes,
            'contacts_count'  => $this->whenCounted('contacts'),
            'primary_contact' => new ContactResource($this->whenLoaded('primaryContact')),
            'contacts'        => ContactResource::collection($this->whenLoaded('contacts')),
            'created_by'      => $this->whenLoaded('user', fn () => $this->user->name),
            'created_at'      => $this->created_at?->toDateTimeString(),
            'updated_at'      => $this->updated_at?->toDateTimeString(),
        ];
    }
}
