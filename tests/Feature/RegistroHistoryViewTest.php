<?php

namespace Tests\Feature;

use App\Models\Producto;
use App\Models\Registro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
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

    public function test_it_filters_movements_by_operation_product_and_date_range(): void
    {
        $this->createProduct('Teclado mecánico', 'AGREGAR', '2026-09-15 10:00:00');
        $this->createProduct('Teclado mecánico', 'RESTAR', '2026-09-16 10:00:00');
        $this->createProduct('Mouse óptico', 'AGREGAR', '2026-09-16 10:00:00');
        $this->createProduct('Teclado mecánico', 'AGREGAR', '2026-09-20 10:00:00');

        $response = $this->get(route('registros.index', [
            'operacion' => 'AGREGAR',
            'producto' => 'Teclado',
            'fecha_desde' => '2026-09-14',
            'fecha_hasta' => '2026-09-17',
        ]));

        $response->assertOk();
        $response->assertSee('movimientos encontrados');
        $response->assertSee('Teclado mecánico');
        $this->assertSame(1, $response->viewData('registros')->total());
    }

    public function test_it_shows_a_filtered_empty_state_when_no_movements_match(): void
    {
        $response = $this->get(route('registros.index', ['producto' => 'Producto inexistente']));

        $response->assertOk();
        $response->assertSee('No hay movimientos que coincidan con los filtros seleccionados.');
    }

    public function test_it_rejects_unknown_operations_and_invalid_date_ranges(): void
    {
        $response = $this->get(route('registros.index', [
            'operacion' => 'BORRAR_TODO',
            'fecha_desde' => '2026-09-20',
            'fecha_hasta' => '2026-09-10',
        ]));

        $response->assertSessionHasErrors(['operacion', 'fecha_hasta']);
    }

    public function test_pagination_keeps_the_active_filters(): void
    {
        for ($index = 1; $index <= 51; $index++) {
            $this->createProduct('Teclado para paginar '.$index, 'AGREGAR', '2026-09-15 10:00:00');
        }

        $response = $this->get(route('registros.index', [
            'operacion' => 'AGREGAR',
            'producto' => 'Teclado para paginar',
            'fecha_desde' => '2026-09-15',
        ]));

        $response->assertOk();
        $response->assertSee('operacion=AGREGAR');
        $response->assertSee('producto=Teclado%20para%20paginar');
        $this->assertSame(50, $response->viewData('registros')->count());
    }

    private function createProduct(string $name, string $operation, string $createdAt): void
    {
        $producto = Producto::create([
            'nombre' => $name,
            'precio' => '10.00',
            'cantidad' => 2,
        ]);

        Registro::create([
            'id_producto' => $producto->id,
            'operacion' => $operation,
            'nombre_producto' => $name,
            'cantidad_antes_operacion' => 1,
            'cantidad_despues_operacion' => 2,
            'precio_antes_operacion' => '9.00',
            'precio_despues_operacion' => '10.00',
            'created_at' => Carbon::parse($createdAt),
            'updated_at' => Carbon::parse($createdAt),
        ]);
    }
}
