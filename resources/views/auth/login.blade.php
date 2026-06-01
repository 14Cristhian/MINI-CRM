@extends('layouts.guest')
@section('title', 'Iniciar sesión')

@section('content')

<div class="rounded-2xl border p-8"
     style="background: var(--bg-surface); border-color: var(--border-subtle); box-shadow: var(--shadow-lg)">

    <h2 class="text-[1.125rem] font-bold mb-1" style="color: var(--text-primary)">Bienvenido de nuevo</h2>
    <p class="text-[0.8125rem] mb-7" style="color: var(--text-muted)">Ingresa tus credenciales para continuar</p>

    @if ($errors->any())
        <div class="flex gap-2.5 p-3.5 rounded-xl border mb-5 text-[0.8125rem]"
             style="background: var(--color-error-bg); border-color: var(--color-error); color: var(--color-error)">
            <x-heroicon-s-exclamation-circle class="w-4 h-4 shrink-0 mt-0.5"/>
            <div>
                @foreach ($errors->all() as $error)
                    <p class="m-0 leading-snug">{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="block text-xs font-semibold mb-1.5" style="color: var(--text-secondary)">
                Correo electrónico
            </label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" style="color: var(--text-muted)">
                    <x-heroicon-s-envelope class="w-4 h-4"/>
                </span>
                <input
                    id="email" name="email" type="email"
                    value="{{ old('email') }}"
                    required autofocus autocomplete="email"
                    placeholder="tu@correo.com"
                    class="w-full pl-9 pr-3.5 py-2.5 rounded-xl text-sm border transition-all outline-none
                           focus:ring-[3px]
                           {{ $errors->has('email') ? 'border-red-400 focus:border-red-500 focus:ring-red-100' : 'focus:border-indigo-500 focus:ring-indigo-100' }}"
                    style="background: var(--bg-surface); color: var(--text-primary);
                           border-color: {{ $errors->has('email') ? 'var(--color-error)' : 'var(--border-default)' }}"
                >
            </div>
            @error('email')
                <p class="mt-1.5 text-xs" style="color: var(--color-error)">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-xs font-semibold mb-1.5" style="color: var(--text-secondary)">
                Contraseña
            </label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" style="color: var(--text-muted)">
                    <x-heroicon-s-lock-closed class="w-4 h-4"/>
                </span>
                <input
                    id="password" name="password" type="password"
                    required autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full pl-9 pr-3.5 py-2.5 rounded-xl text-sm border transition-all outline-none
                           focus:ring-[3px] focus:border-indigo-500 focus:ring-indigo-100"
                    style="background: var(--bg-surface); color: var(--text-primary); border-color: var(--border-default)"
                >
            </div>
        </div>

        <button
            type="submit"
            class="w-full py-2.5 mt-2 rounded-xl text-sm font-semibold text-white
                   bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 transition-colors"
            style="box-shadow: 0 2px 12px rgba(99,102,241,.35)"
        >
            Ingresar al sistema
        </button>
    </form>
</div>

<p class="text-center mt-5 text-[0.8125rem]" style="color: var(--text-muted)">
    ¿No tienes cuenta?
    <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
        Regístrate gratis
    </a>
</p>

@endsection
