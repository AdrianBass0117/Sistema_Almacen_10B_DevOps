<?php

namespace Tests\Feature;

use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ProductoValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_a_product_with_the_minimum_valid_values(): void
    {
        $response = $this->post(route('productos.store'), [
            'nombre' => 'Producto válido',
            'precio' => '0.01',
            'cantidad' => '1',
        ]);

        $response->assertRedirect(route('productos.index'));
        $this->assertDatabaseHas('productos', [
            'nombre' => 'Producto válido',
            'precio' => '0.01',
            'cantidad' => 1,
        ]);
    }

    public function test_it_rejects_non_positive_prices_and_quantities(): void
    {
        $invalidInputs = [
            ['precio' => '0', 'cantidad' => '1', 'error' => 'precio'],
            ['precio' => '-1', 'cantidad' => '1', 'error' => 'precio'],
            ['precio' => '0.001', 'cantidad' => '1', 'error' => 'precio'],
            ['precio' => '1.00', 'cantidad' => '0', 'error' => 'cantidad'],
            ['precio' => '1.00', 'cantidad' => '-1', 'error' => 'cantidad'],
            ['precio' => '1.00', 'cantidad' => '1.5', 'error' => 'cantidad'],
        ];

        foreach ($invalidInputs as $input) {
            $response = $this->post(route('productos.store'), [
                'nombre' => 'Producto de prueba',
                'precio' => $input['precio'],
                'cantidad' => $input['cantidad'],
            ]);

            $response->assertSessionHasErrors($input['error']);
        }

        $this->assertDatabaseCount('productos', 0);
    }

    public function test_it_rejects_html_tags_in_product_names(): void
    {
        $response = $this->post(route('productos.store'), [
            'nombre' => '<script>alert(1)</script>',
            'precio' => '10.00',
            'cantidad' => '1',
        ]);

        $response->assertSessionHasErrors('nombre');
        $this->assertDatabaseCount('productos', 0);
    }

    public function test_sql_injection_like_product_name_is_stored_as_plain_text(): void
    {
        $name = "Producto'); DROP TABLE productos;--";

        $response = $this->post(route('productos.store'), [
            'nombre' => $name,
            'precio' => '10.00',
            'cantidad' => '1',
        ]);

        $response->assertRedirect(route('productos.index'));
        $this->assertDatabaseHas('productos', ['nombre' => $name]);
        $this->assertTrue(Schema::hasTable('productos'));
    }

    public function test_update_rejects_invalid_price_quantity_and_html_name(): void
    {
        $producto = Producto::create([
            'nombre' => 'Producto original',
            'precio' => '10.00',
            'cantidad' => 2,
        ]);

        $invalidInputs = [
            ['nombre' => 'Producto actualizado', 'precio' => '0', 'cantidad' => '2', 'error' => 'precio'],
            ['nombre' => 'Producto actualizado', 'precio' => '10.00', 'cantidad' => '0', 'error' => 'cantidad'],
            ['nombre' => '<img src=x onerror=alert(1)>', 'precio' => '10.00', 'cantidad' => '2', 'error' => 'nombre'],
        ];

        foreach ($invalidInputs as $input) {
            $response = $this->put(route('productos.update', $producto), [
                'nombre' => $input['nombre'],
                'precio' => $input['precio'],
                'cantidad' => $input['cantidad'],
            ]);

            $response->assertSessionHasErrors($input['error']);
        }

        $this->assertDatabaseHas('productos', [
            'id' => $producto->id,
            'nombre' => 'Producto original',
            'precio' => '10.00',
            'cantidad' => 2,
        ]);
    }
}
