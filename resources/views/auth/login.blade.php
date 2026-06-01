@extends('layouts.guest')
@section('title', 'Iniciar sesión')

@section('content')
<div style="
    background: var(--bg-surface);
    border: 1px solid var(--border-subtle);
    border-radius: 16px;
    padding: 2rem;
    box-shadow: var(--shadow-lg)
">
    <h2 style="font-size:1.125rem; font-weight:700; color:var(--text-primary); margin:0 0 0.25rem">
        Bienvenido de nuevo
    </h2>
    <p style="font-size:0.8125rem; color:var(--text-muted); margin:0 0 1.75rem">
        Ingresa tus credenciales para continuar
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

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div style="margin-bottom:1rem">
            <label for="email" style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.375rem">
                Correo electrónico
            </label>
            <input
                id="email" name="email" type="email"
                value="{{ old('email') }}"
                required autofocus autocomplete="email"
                placeholder="tu@correo.com"
                style="
                    width: 100%; padding: 0.625rem 0.875rem;
                    border: 1px solid {{ $errors->has('email') ? 'var(--color-error)' : 'var(--border-default)' }};
                    border-radius: 10px; font-size: 0.875rem;
                    background: var(--bg-surface); color: var(--text-primary);
                    outline: none; box-sizing: border-box; transition: border-color 0.15s, box-shadow 0.15s;
                "
                onfocus="this.style.borderColor='var(--color-brand-500)';this.style.boxShadow='var(--focus-ring)'"
                onblur="this.style.borderColor='var(--border-default)';this.style.boxShadow='none'"
            >
        </div>

        <div style="margin-bottom:1.5rem">
            <label for="password" style="display:block; font-size:0.75rem; font-weight:600; color:var(--text-secondary); margin-bottom:0.375rem">
                Contraseña
            </label>
            <input
                id="password" name="password" type="password"
                required autocomplete="current-password"
                placeholder="••••••••"
                style="
                    width: 100%; padding: 0.625rem 0.875rem;
                    border: 1px solid var(--border-default);
                    border-radius: 10px; font-size: 0.875rem;
                    background: var(--bg-surface); color: var(--text-primary);
                    outline: none; box-sizing: border-box; transition: border-color 0.15s, box-shadow 0.15s;
                "
                onfocus="this.style.borderColor='var(--color-brand-500)';this.style.boxShadow='var(--focus-ring)'"
                onblur="this.style.borderColor='var(--border-default)';this.style.boxShadow='none'"
            >
        </div>

        <button
            type="submit"
            style="
                width: 100%; padding: 0.6875rem 1rem;
                background: var(--color-brand-600); color: white;
                border: none; border-radius: 10px;
                font-size: 0.875rem; font-weight: 600;
                cursor: pointer; transition: background 0.15s;
                box-shadow: 0 2px 8px rgba(99,102,241,0.35)
            "
            onmouseover="this.style.background='var(--color-brand-700)'"
            onmouseout="this.style.background='var(--color-brand-600)'"
        >
            Ingresar al sistema
        </button>
    </form>
</div>

<p style="text-align:center; margin-top:1.25rem; font-size:0.8125rem; color:var(--text-muted)">
    ¿No tienes cuenta?
    <a href="{{ route('register') }}" style="color:var(--color-brand-600); font-weight:600; text-decoration:none">
        Regístrate gratis
    </a>
</p>
@endsection
