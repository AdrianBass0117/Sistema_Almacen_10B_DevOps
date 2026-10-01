<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema Almacén</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('css/productos.css') }}">
    <script src="{{ asset('js/productos.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/registros.css') }}">
    <script src="{{ asset('js/registros.js') }}" defer></script>
    <link rel="stylesheet" href="{{ asset('css/general.css') }}">
</head>
<body class="bg-appleGray text-appleBlack font-sans">
        <header class="navbar">
            <div class="navbar-left">
                <a href="{{ route('inicio') }}" class="navbar-brand">
                    <h1 class="navbar-title">📦 Sistema Almacén</h1>
                </a>
            </div>
            <nav class="navbar-right">
                <a href="{{ route('inicio') }}" class="navbar-link">Inicio</a>
                <a href="{{ route('productos.index') }}" class="navbar-link">📱 Productos</a>
                <a href="{{ route('registros.index') }}" class="navbar-link">📝 Registros</a>
            </nav>
        </header>

    <main class="p-10">
        @yield('content')
    </main>

<!-- Modal de imagen -->
<div id="imageModal" class="image-modal">
    <span class="close-btn">&times;</span>
    <img class="modal-content" id="modalImage">
</div>

</body>
</html>