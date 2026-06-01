<?php

namespace App\Http\Controllers;

use App\Contracts\Services\ContactServiceInterface;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Http\Requests\Contact\UpdateContactRequest;
use App\Http\Traits\ApiResponse;
use App\Models\Client;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ContactServiceInterface $contactService
    ) {}

    public function index(Client $client): JsonResponse
    {
        $contacts = $this->contactService->listByClient($client->id);
        return $this->success($contacts);
    }

    public function store(StoreContactRequest $request, Client $client): JsonResponse
    {
        $contact = $this->contactService->store(
            $request->validated(),
            $client->id,
            $request->user()->id
        );
        return $this->created($contact, 'Contacto creado exitosamente.');
    }

    public function show(Client $client, Contact $contact): JsonResponse
    {
        return $this->success($this->contactService->show($contact->id));
    }

    public function update(UpdateContactRequest $request, Client $client, Contact $contact): JsonResponse
    {
        $updated = $this->contactService->update($contact->id, $request->validated(), $request->user()->id);
        return $this->success($updated, 'Contacto actualizado exitosamente.');
    }

    public function destroy(Request $request, Client $client, Contact $contact): JsonResponse
    {
        $this->contactService->destroy($contact->id, $request->user()->id);
        return $this->noContent('Contacto eliminado correctamente.');
    }
}
