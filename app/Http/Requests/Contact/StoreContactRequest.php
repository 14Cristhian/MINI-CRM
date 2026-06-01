<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clientId = $this->route('client');

        return [
            'name'       => ['required', 'string', 'max:255'],
            'email'      => [
                'required',
                'email',
                "unique:contacts,email,NULL,id,client_id,{$clientId}",
            ],
            'phone'      => ['nullable', 'string', 'max:20'],
            'position'   => ['nullable', 'string', 'max:255'],
            'is_primary' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'  => 'El nombre del contacto es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email'    => 'Ingresa un correo electrónico válido.',
            'email.unique'   => 'Este correo ya existe para este cliente.',
        ];
    }
}
