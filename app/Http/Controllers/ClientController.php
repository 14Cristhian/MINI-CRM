<?php

namespace App\Http\Controllers;

use App\Contracts\Services\ClientServiceInterface;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Traits\ApiResponse;
use App\Models\Client;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ClientController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly ClientServiceInterface $clientService
    ) {}

    public function index(): View
    {
        return view('clients.index');
    }

    public function apiIndex(Request $request): JsonResponse
    {
        $clients = $this->clientService->list($request->only(['search', 'status', 'sort_by', 'sort_dir']));
        return $this->paginated($clients);
    }

    public function show(int $id): View
    {
        $client = $this->clientService->show($id);
        return view('clients.show', compact('client'));
    }

    public function apiShow(int $id): JsonResponse
    {
        $client = $this->clientService->show($id);
        return $this->success($client->load('contacts', 'user'));
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function store(StoreClientRequest $request): JsonResponse
    {
        $client = $this->clientService->store($request->validated(), $request->user()->id);
        return $this->created($client, 'Cliente creado exitosamente.');
    }

    public function edit(int $id): View
    {
        $client = $this->clientService->show($id);
        return view('clients.edit', compact('client'));
    }

    public function update(UpdateClientRequest $request, int $id): JsonResponse
    {
        $client = $this->clientService->update($id, $request->validated(), $request->user()->id);
        return $this->success($client, 'Cliente actualizado exitosamente.');
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $this->clientService->destroy($id, $request->user()->id);
        return $this->noContent('Cliente eliminado correctamente.');
    }

    public function apiStats(): JsonResponse
    {
        $counts = Client::query()
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN status = 'activo'    THEN 1 ELSE 0 END) as activo")
            ->selectRaw("SUM(CASE WHEN status = 'inactivo'  THEN 1 ELSE 0 END) as inactivo")
            ->selectRaw("SUM(CASE WHEN status = 'prospecto' THEN 1 ELSE 0 END) as prospecto")
            ->first();

        return $this->success([
            'total'     => (int) $counts->total,
            'activo'    => (int) $counts->activo,
            'inactivo'  => (int) $counts->inactivo,
            'prospecto' => (int) $counts->prospecto,
            'contacts'  => Contact::count(),
        ]);
    }
}
