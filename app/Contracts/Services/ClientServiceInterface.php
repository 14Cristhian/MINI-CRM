<?php

namespace App\Contracts\Services;

use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ClientServiceInterface
{
    public function list(array $filters): LengthAwarePaginator;

    public function getForExport(array $filters): Collection;

    public function show(int $id): Client;

    public function store(array $data, int $userId): Client;

    public function update(int $id, array $data, int $userId): Client;

    public function destroy(int $id, int $userId): bool;
}
