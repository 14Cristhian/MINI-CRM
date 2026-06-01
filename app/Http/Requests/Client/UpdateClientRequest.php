<?php

namespace App\Http\Requests\Client;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clientId = $this->route('client');

        return [
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', Rule::unique('clients', 'email')->ignore($clientId)],
            'phone'   => ['nullable', 'string', 'max:20'],
            'company' => ['required', 'string', 'max:255'],
            'status'  => ['required', Rule::in(['activo', 'inactivo', 'prospecto'])],
            'notes'   => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'    => 'El nombre del cliente es obligatorio.',
            'email.required'   => 'El correo electrónico es obligatorio.',
            'email.email'      => 'Ingresa un correo electrónico válido.',
            'email.unique'     => 'Este correo ya está en uso por otro cliente.',
            'company.required' => 'La empresa es obligatoria.',
            'status.required'  => 'El estado es obligatorio.',
            'status.in'        => 'El estado debe ser activo, inactivo o prospecto.',
        ];
    }
}
