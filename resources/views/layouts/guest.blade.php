<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — {{ config('app.name', 'Mini CRM') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="background: var(--bg-app); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem">

    <div style="width: 100%; max-width: 400px">
        <!-- Logo / Brand -->
        <div style="text-align: center; margin-bottom: 2rem">
            <div style="
                width: 48px; height: 48px; border-radius: 14px;
                background: linear-gradient(135deg, #6366f1, #4338ca);
                display: inline-flex; align-items: center; justify-content: center;
                margin-bottom: 1rem;
                box-shadow: 0 8px 24px rgba(99,102,241,0.3)
            ">
                <svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:22px;height:22px">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <h1 style="font-size:1.375rem; font-weight:700; color:var(--text-primary); margin:0">Mini CRM</h1>
            <p style="font-size:0.8125rem; color:var(--text-muted); margin-top:0.25rem">Gestión de clientes y contactos</p>
        </div>

        @yield('content')
    </div>

</body>
</html>
