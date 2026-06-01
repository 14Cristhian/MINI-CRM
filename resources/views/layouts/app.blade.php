<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Panel') — {{ config('app.name', 'Mini CRM') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Layout responsive sin JavaScript */
        .app-topbar  { left: 0; }
        .app-main    { padding-top: 60px; padding-left: 0; }

        @media (min-width: 1024px) {
            .app-topbar { left: 240px; }
            .app-main   { padding-left: 240px; }
        }
    </style>
</head>
<body style="background: var(--bg-app); min-height: 100vh">

    {{-- Sidebar (lleva el user para la sección inferior) --}}
    <div
        id="app-sidebar"
        data-user="{{ json_encode([
            'name'      => auth()->user()->name,
            'csrf'      => csrf_token(),
            'logoutUrl' => route('logout'),
        ]) }}"
    ></div>

    {{-- Topbar --}}
    <div id="app-topbar" class="app-topbar fixed top-0 right-0 z-20"></div>

    {{-- Toast container (siempre en top-right) --}}
    <div id="app-toasts"></div>

    {{-- Contenido principal --}}
    <main class="app-main transition-all duration-250">
        <div class="px-6 py-6 max-w-screen-xl mx-auto">
            @yield('content')
        </div>
    </main>

</body>
</html>
