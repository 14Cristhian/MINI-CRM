<?php

namespace App\Contracts\Repositories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;

interface ContactRepositoryInterface
{
    public function getByClient(int $clientId): Collection;

    public function findById(int $id): ?Contact;

    public function findByIdOrFail(int $id): Contact;

    public function create(array $data): Contact;

    public function update(Contact $contact, array $data): Contact;

    public function delete(Contact $contact): bool;

    public function clearPrimaryForClient(int $clientId, ?int $excludeId = null): void;
}
