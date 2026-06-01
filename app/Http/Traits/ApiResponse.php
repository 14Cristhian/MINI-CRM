<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    protected function success(mixed $data = null, string $message = 'OK', int $status = 200): JsonResponse
    {
        $payload = ['success' => true, 'message' => $message];
        if (!is_null($data)) $payload['data'] = $data;
        return response()->json($payload, $status);
    }

    protected function created(mixed $data, string $message = 'Recurso creado.'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    protected function noContent(string $message = 'Eliminado correctamente.'): JsonResponse
    {
        return response()->json(['success' => true, 'message' => $message]);
    }

    protected function error(string $message, int $status = 400, array $errors = []): JsonResponse
    {
        $payload = ['success' => false, 'message' => $message];
        if ($errors) $payload['errors'] = $errors;
        return response()->json($payload, $status);
    }

    protected function paginated(mixed $paginator): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $paginator->items(),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }
}
