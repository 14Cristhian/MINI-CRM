<?php

namespace App\Contracts\Services;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Collection;

interface ContactServiceInterface
{
    public function listByClient(int $clientId): Collection;

    public function show(int $id): Contact;

    public function store(array $data, int $clientId, int $userId): Contact;

    public function update(int $id, array $data, int $userId): Contact;

    public function destroy(int $id, int $userId): void;
}
