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
        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" maxlength="255" required>
        @error('nombre')
            <div class="toast error">{{ $message }}</div>
        @enderror

        <label for="precio">💰 Precio</label>
        <input type="number" step="0.01" min="0.01" name="precio" id="precio" value="{{ old('precio') }}" required>
        @error('precio')
            <div class="toast error">{{ $message }}</div>
        @enderror

        <label for="cantidad">📦 Cantidad</label>
        <input type="number" min="1" step="1" name="cantidad" id="cantidad" value="{{ old('cantidad') }}" required>
        @error('cantidad')
            <div class="toast error">{{ $message }}</div>
        @enderror

        <label for="imagen">🖼 Imagen (opcional)</label>
        <input type="file" name="imagen" id="imagen" accept="image/*">

        <div class="form-actions">
            <a href="{{ route('productos.index') }}" class="btn-cancelar">← Cancelar</a>
            <button type="submit" class="btn-guardar">💾 Guardar</button>
        </div>
    </form>
</div>
@endsection
