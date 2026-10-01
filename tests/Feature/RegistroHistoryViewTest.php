<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Models\Registro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistroHistoryViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_shows_an_empty_state_when_there_are_no_movements(): void
    {
        $response = $this->get(route('registros.index'));

        $response->assertOk();
        $response->assertSee('No hay movimientos registrados');
        $response->assertSee('0');
    }

    public function test_it_displays_operation_badges_and_before_after_values(): void
    {
        $producto = Producto::create([
            'nombre' => 'Teclado de prueba',
            'precio' => '120.00',
            'cantidad' => 8,
        ]);

        Registro::create([
            'id_producto' => $producto->id,
            'operacion' => 'AGREGAR',
            'nombre_producto' => $producto->nombre,
            'cantidad_antes_operacion' => 3,
            'cantidad_despues_operacion' => 8,
            'precio_antes_operacion' => '100.00',
            'precio_despues_operacion' => '120.00',
        ]);

        $response = $this->get(route('registros.index'));

        $response->assertOk();
        $response->assertSee('Entrada');
        $response->assertSee('Teclado de prueba');
        $response->assertSee('3');
        $response->assertSee('8');
        $response->assertSee('$100.00');
        $response->assertSee('$120.00');
        $response->assertSee('Aumento');
    }

    public function test_it_paginates_history_after_fifty_movements(): void
    {
        $producto = Producto::create([
            'nombre' => 'Producto con historial',
            'precio' => '10.00',
            'cantidad' => 1,
        ]);

        foreach (range(1, 51) as $index) {
            Registro::create([
                'id_producto' => $producto->id,
                'operacion' => 'AGREGAR',
                'nombre_producto' => $producto->nombre,
                'cantidad_antes_operacion' => $index,
                'cantidad_despues_operacion' => $index + 1,
                'precio_antes_operacion' => '10.00',
                'precio_despues_operacion' => '10.00',
            ]);
        }

        $response = $this->get(route('registros.index'));

        $response->assertOk();
        $response->assertSee('51');
        $response->assertSee('movimientos');
        $response->assertSee('Pagination Navigation');
        $this->assertSame(50, $response->viewData('registros')->count());
    }
}
