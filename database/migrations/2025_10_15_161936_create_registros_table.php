<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('registros', function (Blueprint $table) {
            $table->id();

            // Relación con producto
            $table->unsignedBigInteger('id_producto');

            // Operación realizada
            $table->string('operacion'); // Ej: 'creado', 'editado', 'borrado'

            // Nombre del producto en el momento
            $table->string('nombre_producto');

            // Estado antes de la operación
            $table->integer('cantidad_antes_operacion')->nullable();
            $table->decimal('precio_antes_operacion', 10, 2)->nullable();

            // Estado después de la operación
            $table->integer('cantidad_despues_operacion')->nullable();
            $table->decimal('precio_despues_operacion', 10, 2)->nullable();

            // Timestamps para trazabilidad
            $table->timestamps();

            // Clave foránea
            $table->foreign('id_producto')->references('id')->on('productos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registros');
    }
};
