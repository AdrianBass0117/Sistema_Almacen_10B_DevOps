@extends('layouts.app')

@section('content')
@if(session('mensaje'))
    <div class="toast">{{ session('mensaje') }}</div>
@endif

<div class="registros-container">
    <h1 class="registros-title">📜 Historial de Registros</h1>

    <div class="table-wrapper">
        <table class="registros-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>🧾 Operación</th>
                    <th>📛 Producto</th>
                    <th>📦 Cantidad Antes</th>
                    <th>📦 Cantidad Después</th>
                    <th>💰 Precio Antes</th>
                    <th>💰 Precio Después</th>
                    <th>📅 Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($registros as $registro)
                    <tr>
                        <td>{{ $registro->id }}</td>
                        <td>{{ $registro->operacion }}</td>
                        <td>{{ $registro->nombre_producto }}</td>
                        <td>{{ $registro->cantidad_antes_operacion ?? '—' }}</td>
                        <td>{{ $registro->cantidad_despues_operacion ?? '—' }}</td>
                        <td>${{ number_format($registro->precio_antes_operacion ?? 0, 2) }}</td>
                        <td>${{ number_format($registro->precio_despues_operacion ?? 0, 2) }}</td>
                        <td>{{ $registro->created_at->format('d M Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
