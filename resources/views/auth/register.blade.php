@extends('layouts.guest')
@section('title', 'Crear cuenta')

@section('content')
<div style="
    background: var(--bg-surface);
    border: 1px solid var(--border-subtle);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: var(--shadow-lg)
">
    <h2 style="font-size:1.125rem; font-weight:700; color:var(--text-primary); margin:0 0 0.25rem">
        Crear una cuenta
    </h2>
    <p style="font-size:0.8125rem; color:var(--text-muted); margin:0 0 1.75rem">
        Completa los datos para comenzar
    </p>

    @if ($errors->any())
        <div style="
            padding: 0.875rem 1rem;
            background: var(--color-error-bg);
            border: 1px solid var(--color-error);
            border-radius: 10px;
            margin-bottom: 1.25rem;
            font-size: 0.8125rem;
            color: var(--color-error)
        ">
            @foreach ($errors->all() as $error)
                <p style="margin:0">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        @foreach ([
            ['id'=>'name',                 'label'=>'Nombre completo',      'type'=>'text',     'placeholder'=>'Juan Pérez',       'autocomplete'=>'name'],
            ['id'=>'email',                'label'=>'Correo electrónico',   'type'=>'email',    'placeholder'=>'tu@correo.com',    'autocomplete'=>'email'],
            ['id'=>'password',             'label'=>'Contraseña',           'type'=>'password', 'placeholder'=>'Mínimo 8 caracteres', 'autocomplete'=>'new-password'],
            ['id'=>'password_confirmation','label'=>'Confirmar contraseña', 'type'=>'password', 'placeholder'=>'Repite la contraseña', 'autocomplete'=>'new-password'],
        ] as $field)
            <div style="margin-bottom:1rem">
                <label for="{{ $field['id'] }}" style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.375rem">
                    {{ $field['label'] }}
                </label>
                <input
                    id="{{ $field['id'] }}"
                    name="{{ $field['id'] }}"
                    type="{{ $field['type'] }}"
                    value="{{ $field['type'] !== 'password' ? old($field['id']) : '' }}"
                    placeholder="{{ $field['placeholder'] }}"
                    autocomplete="{{ $field['autocomplete'] }}"
                    required
                    style="
                        width: 100%; padding: 0.625rem 0.875rem;
                        border: 1px solid {{ $errors->has($field['id']) ? 'var(--color-error)' : 'var(--border-default)' }};
                        border-radius: 10px; font-size: 0.875rem;
                        background: var(--bg-surface); color: var(--text-primary);
                        outline: none; box-sizing: border-box; transition: border-color 0.15s, box-shadow 0.15s;
                    "
                    onfocus="this.style.borderColor='var(--color-brand-500)';this.style.boxShadow='var(--focus-ring)'"
                    onblur="this.style.borderColor='{{ $errors->has($field['id']) ? 'var(--color-error)' : 'var(--border-default)' }}';this.style.boxShadow='none'"
                >
                @error($field['id'])
                    <p style="font-size:0.75rem; color:var(--color-error); margin:0.25rem 0 0">{{ $message }}</p>
                @enderror
            </div>
        @endforeach

        <button
            type="submit"
            style="
                width: 100%; padding: 0.6875rem 1rem; margin-top:0.5rem;
                background: var(--color-brand-600); color: white;
                border: none; border-radius: 10px;
                font-size: 0.875rem; font-weight: 600;
                cursor: pointer; transition: background 0.15s;
                box-shadow: 0 2px 8px rgba(99,102,241,0.35)
            "
            onmouseover="this.style.background='var(--color-brand-700)'"
            onmouseout="this.style.background='var(--color-brand-600)'"
        >
            Crear cuenta
        </button>
    </form>
</div>

<p style="text-align:center; margin-top:1.25rem; font-size:0.8125rem; color:var(--text-muted)">
    ¿Ya tienes cuenta?
    <a href="{{ route('login') }}" style="color:var(--color-brand-600); font-weight:600; text-decoration:none">
        Inicia sesión
    </a>
</p>
@endsection
