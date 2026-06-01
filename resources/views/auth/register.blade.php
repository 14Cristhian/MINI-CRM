@extends('layouts.guest')
@section('title', 'Crear cuenta')

@section('content')

<div class="rounded-2xl border p-8"
     style="background: var(--bg-surface); border-color: var(--border-subtle); box-shadow: var(--shadow-lg)">

    <h2 class="text-[1.125rem] font-bold mb-1" style="color: var(--text-primary)">Crea tu cuenta</h2>
    <p class="text-[0.8125rem] mb-7" style="color: var(--text-muted)">Completa los datos para comenzar</p>

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

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        {{-- Nombre --}}
        <div>
            <label for="name" class="block text-xs font-semibold mb-1.5" style="color: var(--text-secondary)">
                Nombre completo
            </label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" style="color: var(--text-muted)">
                    <x-heroicon-s-user class="w-4 h-4"/>
                </span>
                <input
                    id="name" name="name" type="text"
                    value="{{ old('name') }}"
                    required autofocus autocomplete="name"
                    placeholder="Juan Pérez"
                    class="w-full pl-9 pr-3.5 py-2.5 rounded-xl text-sm border transition-all outline-none
                           focus:ring-[3px]
                           {{ $errors->has('name') ? 'border-red-400 focus:border-red-500 focus:ring-red-100' : 'focus:border-indigo-500 focus:ring-indigo-100' }}"
                    style="background: var(--bg-surface); color: var(--text-primary);
                           border-color: {{ $errors->has('name') ? 'var(--color-error)' : 'var(--border-default)' }}"
                >
            </div>
            @error('name')
                <p class="mt-1.5 text-xs" style="color: var(--color-error)">{{ $message }}</p>
            @enderror
        </div>

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
                    required autocomplete="email"
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
                <span class="font-normal ml-1" style="color: var(--text-muted)">(mínimo 8 caracteres)</span>
            </label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" style="color: var(--text-muted)">
                    <x-heroicon-s-lock-closed class="w-4 h-4"/>
                </span>
                <input
                    id="password" name="password" type="password"
                    required autocomplete="new-password"
                    placeholder="••••••••"
                    class="w-full pl-9 pr-3.5 py-2.5 rounded-xl text-sm border transition-all outline-none
                           focus:ring-[3px]
                           {{ $errors->has('password') ? 'border-red-400 focus:border-red-500 focus:ring-red-100' : 'focus:border-indigo-500 focus:ring-indigo-100' }}"
                    style="background: var(--bg-surface); color: var(--text-primary);
                           border-color: {{ $errors->has('password') ? 'var(--color-error)' : 'var(--border-default)' }}"
                >
            </div>
            @error('password')
                <p class="mt-1.5 text-xs" style="color: var(--color-error)">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm password --}}
        <div>
            <label for="password_confirmation" class="block text-xs font-semibold mb-1.5" style="color: var(--text-secondary)">
                Confirmar contraseña
            </label>
            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none" style="color: var(--text-muted)">
                    <x-heroicon-s-lock-closed class="w-4 h-4"/>
                </span>
                <input
                    id="password_confirmation" name="password_confirmation" type="password"
                    required autocomplete="new-password"
                    placeholder="••••••••"
                    class="w-full pl-9 pr-3.5 py-2.5 rounded-xl text-sm border transition-all outline-none
                           focus:ring-[3px] focus:border-indigo-500 focus:ring-indigo-100"
                    style="background: var(--bg-surface); color: var(--text-primary); border-color: var(--border-default)"
                >
            </div>
        </div>

        <button
            type="submit"
            class="w-full py-2.5 mt-1 rounded-xl text-sm font-semibold text-white
                   bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 transition-colors"
            style="box-shadow: 0 2px 12px rgba(99,102,241,.35)"
        >
            Crear cuenta
        </button>
    </form>
</div>

<p class="text-center mt-5 text-[0.8125rem]" style="color: var(--text-muted)">
    ¿Ya tienes cuenta?
    <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 transition-colors">
        Inicia sesión
    </a>
</p>

@endsection
