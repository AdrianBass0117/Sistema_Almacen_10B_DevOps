@extends('layouts.app')

@section('content')
@if(session('mensaje'))
    <div class="toast">{{ session('mensaje') }}</div>
@endif

<div class="form-container">
    <h1 class="form-title">➕ Agregar Producto</h1>

    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data" class="form-box">
        @csrf

        <label for="nombre">📛 Nombre</label>
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required>

        <label for="precio">💰 Precio</label>
        <input type="number" step="0.01" name="precio" id="precio" value="{{ old('precio') }}" required>

        <label for="cantidad">📦 Cantidad</label>
        <input type="number" name="cantidad" id="cantidad" value="{{ old('cantidad') }}" required>

        <label for="imagen">🖼 Imagen (opcional)</label>
        <input type="file" name="imagen" id="imagen" accept="image/*">

        <div class="form-actions">
            <a href="{{ route('productos.index') }}" class="btn-cancelar">← Cancelar</a>
            <button type="submit" class="btn-guardar">💾 Guardar</button>
        </div>
    </form>
</div>
@endsection
