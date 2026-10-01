<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use Illuminate\Http\Request;

class RegistroController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'operacion' => 'nullable|in:AGREGAR,RESTAR,ACTUALIZAR_PRECIO,BORRAR_PRODUCTO',
            'producto' => 'nullable|string|max:255',
            'fecha_desde' => 'nullable|date_format:Y-m-d',
            'fecha_hasta' => 'nullable|date_format:Y-m-d|after_or_equal:fecha_desde',
        ]);

        $query = Registro::query();

        if (! empty($filters['operacion'])) {
            $query->where('operacion', $filters['operacion']);
        }

        if (! empty($filters['producto'])) {
            $query->where('nombre_producto', 'like', '%'.$filters['producto'].'%');
        }

        if (! empty($filters['fecha_desde'])) {
            $query->whereDate('created_at', '>=', $filters['fecha_desde']);
        }

        if (! empty($filters['fecha_hasta'])) {
            $query->whereDate('created_at', '<=', $filters['fecha_hasta']);
        }

        $registros = $query->orderByDesc('created_at')->orderByDesc('id')
            ->paginate(50)
            ->appends($filters);

        return view('registros.index', [
            'registros' => $registros,
            'filters' => $filters,
            'hasActiveFilters' => collect($filters)->contains(fn ($value) => filled($value)),
        ]);
    }
}
