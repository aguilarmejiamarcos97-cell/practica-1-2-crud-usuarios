<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Práctica 1.2 - CRUD de usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand navbar-dark bg-dark">
        <div class="container">
            <span class="navbar-brand">Práctica 1.2 · CRUD de usuarios</span>

            @auth
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('usuarios.index') }}">Usuarios</a></li>

                    {{-- La papelera solo se muestra al administrador --}}
                    @if (auth()->user()->esAdmin())
                        <li class="nav-item"><a class="nav-link" href="{{ route('usuarios.papelera') }}">Papelera</a></li>
                    @endif
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <span class="text-white">
                        {{ auth()->user()->name }}
                        <span class="badge {{ auth()->user()->esAdmin() ? 'bg-danger' : 'bg-info text-dark' }}">
                            {{ auth()->user()->role }}
                        </span>
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-light btn-sm">Cerrar sesión</button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

    <main class="container py-4">
        @if (session('ok'))
            <div class="alert alert-success">{{ session('ok') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
