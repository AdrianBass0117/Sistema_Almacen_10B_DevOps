@extends('layouts.app')

@section('content')
@if(session('mensaje'))
    <div class="toast">{{ session('mensaje') }}</div>
@endif

<div class="registros-container">
    <div class="registros-header">
        <div>
            <p class="registros-eyebrow">Trazabilidad del inventario</p>
            <h1 class="registros-title">Historial de movimientos</h1>
            <p class="registros-description">Consulta cómo cambian las existencias y los precios de tus productos.</p>
        </div>
        <div class="registros-count">
            <span class="registros-count-value">{{ $registros->total() }}</span>
            <span class="registros-count-label">movimientos</span>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="registros-table">
            <thead>
                <tr>
                    <th scope="col">Movimiento</th>
                    <th scope="col">Producto</th>
                    <th scope="col">Cambio de existencias</th>
                    <th scope="col">Cambio de precio</th>
                    <th scope="col">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registros as $registro)
                    @php
                        $tipoOperacion = match ($registro->operacion) {
                            'AGREGAR' => ['Entrada', 'entrada'],
                            'RESTAR' => ['Salida', 'salida'],
                            'ACTUALIZAR_PRECIO' => ['Cambio de precio', 'precio'],
                            'BORRAR_PRODUCTO' => ['Producto eliminado', 'eliminado'],
                            default => [$registro->operacion, 'otro'],
                        };
                    @endphp
                    <tr>
                        <td>
                            <span class="operacion-badge operacion-{{ $tipoOperacion[1] }}">
                                <span class="operacion-indicador" aria-hidden="true"></span>
                                {{ $tipoOperacion[0] }}
                            </span>
                            <span class="registro-id">Movimiento #{{ $registro->id }}</span>
                        </td>
                        <td class="producto-registro">{{ $registro->nombre_producto }}</td>
                        <td>
                            <div class="cambio-valor">
                                <span>{{ $registro->cantidad_antes_operacion ?? '—' }}</span>
                                <span class="cambio-flecha" aria-label="cambia a">→</span>
                                <strong>{{ $registro->cantidad_despues_operacion ?? '—' }}</strong>
                            </div>
                            @if($registro->cantidad_antes_operacion !== null && $registro->cantidad_despues_operacion !== null && $registro->cantidad_antes_operacion !== $registro->cantidad_despues_operacion)
                                <span class="cambio-indicador {{ $registro->cantidad_despues_operacion > $registro->cantidad_antes_operacion ? 'cambio-aumento' : 'cambio-disminucion' }}">
                                    {{ $registro->cantidad_despues_operacion > $registro->cantidad_antes_operacion ? 'Aumento' : 'Disminución' }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="cambio-valor">
                                <span>{{ $registro->precio_antes_operacion !== null ? '$'.number_format((float) $registro->precio_antes_operacion, 2) : '—' }}</span>
                                <span class="cambio-flecha" aria-label="cambia a">→</span>
                                <strong>{{ $registro->precio_despues_operacion !== null ? '$'.number_format((float) $registro->precio_despues_operacion, 2) : '—' }}</strong>
                            </div>
                            @if($registro->precio_antes_operacion !== null && $registro->precio_despues_operacion !== null && (float) $registro->precio_antes_operacion !== (float) $registro->precio_despues_operacion)
                                <span class="cambio-indicador {{ (float) $registro->precio_despues_operacion > (float) $registro->precio_antes_operacion ? 'cambio-aumento' : 'cambio-disminucion' }}">
                                    {{ (float) $registro->precio_despues_operacion > (float) $registro->precio_antes_operacion ? 'Aumento' : 'Disminución' }}
                                </span>
                            @endif
                        </td>
                        <td class="fecha-registro">
                            <time datetime="{{ $registro->created_at->toIso8601String() }}">{{ $registro->created_at->format('d/m/Y H:i') }}</time>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="registros-vacio">
                                <span class="registros-vacio-icono" aria-hidden="true">📋</span>
                                <strong>No hay movimientos registrados</strong>
                                <span>Los cambios realizados en los productos aparecerán aquí.</span>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($registros->hasPages())
        <div class="registros-paginacion">
            {{ $registros->links() }}
        </div>
    @endif
</div>
@endsection
