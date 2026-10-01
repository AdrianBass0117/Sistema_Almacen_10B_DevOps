@extends('layouts.app')

@section('content')
@if(session('mensaje'))
    <div class="toast">{{ session('mensaje') }}</div>
@endif

<div class="productos-container">
    <div class="productos-header">
        <h1 class="titulo-inventario">📦 Inventario</h1>
        <a href="{{ route('productos.create') }}" class="btn-agregar">➕ Agregar Producto</a>
    </div>

    <div class="productos-grid">
        @foreach($productos as $producto)
            <div class="card">
                <div class="card-image">
                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}" 
                             alt="Imagen de {{ $producto->nombre }}" 
                             class="clickable-image">
                    @else
                        <div class="no-image">Imagen no disponible</div>
                    @endif
                </div>
                <div class="card-content">
                    <h3>{{ $producto->nombre }}</h3>
                    <p>💰 ${{ number_format($producto->precio, 2) }}</p>
                    <p>📦 Cantidad: {{ $producto->cantidad }}</p>
                </div>
                <div class="card-actions">
                    <a href="{{ route('productos.edit', $producto) }}" class="btn-editar">✏️ Editar</a>
                    <form action="{{ route('productos.destroy', $producto) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-borrar" onclick="return confirm('¿Eliminar este producto?')">🗑 Borrar</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="paginacion">
        {{ $productos->links() }}
    </div>
</div>

<!-- Modal de imagen -->
<div id="imageModal" class="image-modal">
    <span class="close-btn">&times;</span>
    <img class="modal-content" id="modalImage">
</div>
@endsection
