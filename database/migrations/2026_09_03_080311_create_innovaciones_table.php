<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('innovaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('restrict');
            $table->string('titulo', 200);
            $table->text('descripcion');
            $table->string('imagen_portada')->nullable();
            $table->decimal('meta_financiera', 12, 2)->default(0.00);
            $table->decimal('monto_recaudado', 12, 2)->default(0.00);
            $table->enum('estado', ['borrador', 'activo', 'financiado', 'cancelado'])->default('borrador');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('innovaciones');
    }
};
