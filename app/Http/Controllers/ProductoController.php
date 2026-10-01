<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Registro;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::paginate(12);
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        return view('productos.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0',
            'imagen' => 'nullable|image|max:5120', // validación de imagen
        ]);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto = Producto::create($validated);

        Registro::create([
            'id_producto' => $producto->id,
            'nombre_producto' => $producto->nombre,
            'operacion' => 'AGREGAR',
            'cantidad_antes_operacion' => 0,
            'cantidad_despues_operacion' => $producto->cantidad,
            'precio_antes_operacion' => 0,
            'precio_despues_operacion' => $producto->precio,
        ]);

        return redirect()->route('productos.index')->with('mensaje', 'Producto agregado correctamente');
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0',
            'imagen' => 'nullable|image|max:5120', // validación de imagen
        ]);

        if ($request->hasFile('imagen')) {
            $validated['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        // Detectar cambios
        $cambioCantidad = $producto->cantidad !== $validated['cantidad'];
        $cambioPrecio = $producto->precio !== $validated['precio'];

        if ($cambioCantidad || $cambioPrecio) {
            Registro::create([
                'id_producto' => $producto->id,
                'nombre_producto' => $producto->nombre,
                'operacion' => $cambioCantidad ? ($validated['cantidad'] > $producto->cantidad ? 'AGREGAR' : 'RESTAR') : 'ACTUALIZAR_PRECIO',
                'cantidad_antes_operacion' => $producto->cantidad,
                'cantidad_despues_operacion' => $validated['cantidad'],
                'precio_antes_operacion' => $producto->precio,
                'precio_despues_operacion' => $validated['precio'],
            ]);
        }

        $producto->update($validated);

        return redirect()->route('productos.index')->with('mensaje', 'Producto actualizado correctamente');
    }


    public function destroy(Producto $producto)
    {
        Registro::create([
            'id_producto' => $producto->id,
            'nombre_producto' => $producto->nombre,
            'operacion' => 'BORRAR_PRODUCTO',
            'cantidad_antes_operacion' => $producto->cantidad,
            'cantidad_despues_operacion' => null,
            'precio_antes_operacion' => $producto->precio,
            'precio_despues_operacion' => null,
        ]);

        $producto->delete();

        return redirect()->route('productos.index');
    }
}
