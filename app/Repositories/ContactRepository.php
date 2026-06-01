<?php

namespace App\Repositories;

use App\Contracts\Repositories\ContactRepositoryInterface;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;

class ContactRepository implements ContactRepositoryInterface
{
    public function getByClient(int $clientId): Collection
    {
        return Contact::where('client_id', $clientId)
            ->orderByDesc('is_primary')
            ->orderBy('name')
            ->get();
    }

    public function findById(int $id): ?Contact
    {
        return Contact::with('client')->find($id);
    }

    public function findByIdOrFail(int $id): Contact
    {
        return Contact::with('client')->findOrFail($id);
    }

    public function create(array $data): Contact
    {
        return Contact::create($data);
    }

    public function update(Contact $contact, array $data): Contact
    {
        $contact->update($data);
        return $contact->fresh();
    }

    public function delete(Contact $contact): bool
    {
        return $contact->delete();
    }

    public function clearPrimaryForClient(int $clientId, ?int $excludeId = null): void
    {
        $query = Contact::where('client_id', $clientId)->where('is_primary', true);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $query->update(['is_primary' => false]);
    }
}
