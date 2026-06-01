<?php

namespace App\Services;

use App\Contracts\Repositories\ClientRepositoryInterface;
use App\Contracts\Repositories\ContactRepositoryInterface;
use App\Contracts\Services\ContactServiceInterface;
use App\Models\Contact;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;

class ContactService implements ContactServiceInterface
{
    public function __construct(
        private readonly ContactRepositoryInterface $contactRepository,
        private readonly ClientRepositoryInterface $clientRepository,
    ) {}

    public function listByClient(int $clientId): Collection
    {
        $this->clientRepository->findByIdOrFail($clientId);
        return $this->contactRepository->getByClient($clientId);
    }

    public function show(int $id): Contact
    {
        return $this->contactRepository->findByIdOrFail($id);
    }

    public function store(array $data, int $clientId, int $userId): Contact
    {
        $client = $this->clientRepository->findByIdOrFail($clientId);

        if ($client->user_id !== $userId) {
            throw new AuthorizationException('No tienes permiso para agregar contactos a este cliente.');
        }

        if (!empty($data['is_primary']) && $data['is_primary']) {
            $this->contactRepository->clearPrimaryForClient($clientId);
        }

        $data['client_id'] = $clientId;
        $data['user_id']   = $userId;

        return $this->contactRepository->create($data);
    }

    public function update(int $id, array $data, int $userId): Contact
    {
        $contact = $this->contactRepository->findByIdOrFail($id);
        $this->authorizeUser($contact, $userId);

        if (!empty($data['is_primary']) && $data['is_primary']) {
            $this->contactRepository->clearPrimaryForClient($contact->client_id, $id);
        }

        return $this->contactRepository->update($contact, $data);
    }

    public function destroy(int $id, int $userId): void
    {
        $contact = $this->contactRepository->findByIdOrFail($id);
        $this->authorizeUser($contact, $userId);
        $this->contactRepository->delete($contact);
    }

    private function authorizeUser(Contact $contact, int $userId): void
    {
        if ($contact->user_id !== $userId) {
            throw new AuthorizationException('No tienes permiso para modificar este contacto.');
        }
    }
}
