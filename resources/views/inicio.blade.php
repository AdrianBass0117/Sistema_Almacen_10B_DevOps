@extends('layouts.app')

@section('content')
<section class="inicio-container">
    <div class="inicio-hero">
        <span class="inicio-etiqueta">Control de inventario</span>
        <h2>Bienvenido al Sistema de Almacén</h2>
        <p>
            Administra tus productos desde un solo lugar. Consulta existencias,
            actualiza precios y revisa el historial de movimientos para dar
            seguimiento a los cambios del inventario.
        </p>
    </div>

    <div class="inicio-opciones" aria-label="Secciones del sistema">
        <a href="{{ route('productos.index') }}" class="inicio-card">
            <span class="inicio-card-icono" aria-hidden="true">📦</span>
            <span class="inicio-card-contenido">
                <strong>Productos</strong>
                <span>Consulta el inventario, agrega productos o actualiza sus datos.</span>
            </span>
            <span class="inicio-card-flecha" aria-hidden="true">→</span>
        </a>

        <a href="{{ route('registros.index') }}" class="inicio-card">
            <span class="inicio-card-icono" aria-hidden="true">📋</span>
            <span class="inicio-card-contenido">
                <strong>Registros</strong>
                <span>Revisa los movimientos y cambios de precio y existencias.</span>
            </span>
            <span class="inicio-card-flecha" aria-hidden="true">→</span>
        </a>
    </div>
</section>
@endsection
