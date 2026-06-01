<?php

namespace App\Services;

use App\Contracts\Repositories\ClientRepositoryInterface;
use App\Contracts\Services\ClientServiceInterface;
use App\Models\Client;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ClientService implements ClientServiceInterface
{
    public function __construct(
        private readonly ClientRepositoryInterface $clientRepository
    ) {}

    public function list(array $filters): LengthAwarePaginator
    {
        return $this->clientRepository->getAllPaginated($filters);
    }

    public function getForExport(array $filters): Collection
    {
        return $this->clientRepository->getAllForExport($filters);
    }

    public function show(int $id): Client
    {
        return $this->clientRepository->findByIdOrFail($id);
    }

    public function store(array $data, int $userId): Client
    {
        $data['user_id'] = $userId;
        return $this->clientRepository->create($data);
    }

    public function update(int $id, array $data, int $userId): Client
    {
        $client = $this->clientRepository->findByIdOrFail($id);
        $this->authorizeUser($client, $userId);

        return $this->clientRepository->update($client, $data);
    }

    public function destroy(int $id, int $userId): bool
    {
        $client = $this->clientRepository->findByIdOrFail($id);
        $this->authorizeUser($client, $userId);

        return $this->clientRepository->delete($client);
    }

    private function authorizeUser(Client $client, int $userId): void
    {
        if ($client->user_id !== $userId) {
            throw new AuthorizationException('No tienes permiso para modificar este cliente.');
        }
    }
}
