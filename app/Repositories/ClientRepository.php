<?php

namespace App\Repositories;

use App\Contracts\Repositories\ClientRepositoryInterface;
use App\Models\Client;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ClientRepository implements ClientRepositoryInterface
{
    private const SORTABLE = ['name', 'company', 'status', 'contacts_count', 'created_at'];

    public function getAllPaginated(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query   = Client::with(['primaryContact'])->withCount('contacts');
        $sortBy  = in_array($filters['sort_by']  ?? '', self::SORTABLE) ? $filters['sort_by']  : 'created_at';
        $sortDir = ($filters['sort_dir'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        return $query->orderBy($sortBy, $sortDir)->paginate($perPage);
    }

    public function getAllForExport(array $filters): Collection
    {
        $query = Client::with(['primaryContact'])->withCount('contacts');

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        return $query->latest()->get();
    }

    public function findById(int $id): ?Client
    {
        return Client::with(['contacts', 'user'])->find($id);
    }

    public function findByIdOrFail(int $id): Client
    {
        return Client::with(['contacts', 'user'])->findOrFail($id);
    }

    public function create(array $data): Client
    {
        return Client::create($data);
    }

    public function update(Client $client, array $data): Client
    {
        $client->update($data);
        return $client->fresh();
    }

    public function delete(Client $client): bool
    {
        return $client->delete();
    }
}
