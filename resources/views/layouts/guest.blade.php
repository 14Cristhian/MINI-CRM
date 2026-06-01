<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — {{ config('app.name', 'Mini CRM') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex" style="background: var(--bg-app)">

    {{-- ── Panel izquierdo (decorativo, solo lg+) ───────────────────── --}}
    <div class="hidden lg:flex lg:w-[480px] xl:w-[520px] shrink-0 flex-col items-center justify-center p-14 relative overflow-hidden"
         style="background: linear-gradient(150deg, var(--color-brand-700) 0%, var(--color-brand-900) 100%)">

        {{-- Orbes de fondo --}}
        <div class="absolute -top-20 -left-20 w-72 h-72 rounded-full opacity-20"
             style="background: white; filter: blur(70px)"></div>
        <div class="absolute -bottom-16 -right-16 w-56 h-56 rounded-full opacity-15"
             style="background: white; filter: blur(55px)"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 rounded-full opacity-5"
             style="background: white; filter: blur(90px)"></div>

        <div class="relative z-10 w-full max-w-xs">
            {{-- Logo --}}
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-8"
                 style="background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2); box-shadow: 0 8px 32px rgba(0,0,0,.2)">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" class="w-7 h-7">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>

            <h1 class="text-3xl font-bold text-white mb-2 tracking-tight">Mini CRM</h1>
            <p class="text-base mb-10" style="color: rgba(255,255,255,.65)">
                Gestiona clientes y contactos con claridad y velocidad.
            </p>

            {{-- Feature list --}}
            <ul class="space-y-4">
                @foreach ([
                    ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'text' => 'CRUD de clientes con filtros y búsqueda'],
                    ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'text' => 'Contactos por cliente con rol primario'],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'text' => 'Dashboard de estadísticas en tiempo real'],
                    ['icon' => 'M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'text' => 'Exportación a Excel y PDF'],
                ] as $f)
                    <li class="flex items-start gap-3.5">
                        <div class="w-8 h-8 rounded-lg shrink-0 flex items-center justify-center mt-0.5"
                             style="background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.15)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.75"
                                 stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                                <path d="{{ $f['icon'] }}"/>
                            </svg>
                        </div>
                        <span class="text-sm pt-1.5" style="color: rgba(255,255,255,.75)">{{ $f['text'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- ── Panel derecho (formulario) ────────────────────────────────── --}}
    <div class="flex-1 flex items-center justify-center p-6 sm:p-10">
        <div class="w-full max-w-sm">

            {{-- Logo móvil (solo < lg) --}}
            <div class="lg:hidden flex flex-col items-center mb-8">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3"
                     style="background: linear-gradient(135deg, var(--color-brand-500), var(--color-brand-700)); box-shadow: 0 8px 24px rgba(99,102,241,.3)">
                    <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <span class="text-lg font-bold" style="color: var(--text-primary)">Mini CRM</span>
                <span class="text-xs mt-0.5" style="color: var(--text-muted)">Gestión de clientes y contactos</span>
            </div>

            @yield('content')
        </div>
    </div>

</body>
</html>
