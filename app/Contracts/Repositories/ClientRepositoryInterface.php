<?php

namespace App\Contracts\Repositories;

use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ClientRepositoryInterface
{
    public function getAllPaginated(array $filters, int $perPage = 10): LengthAwarePaginator;

    public function getAllForExport(array $filters): Collection;

    public function findById(int $id): ?Client;

    public function findByIdOrFail(int $id): Client;

    public function create(array $data): Client;

    public function update(Client $client, array $data): Client;

    public function delete(Client $client): bool;
}
