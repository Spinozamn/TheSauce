<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('carrito_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carrito_id')->constrained('carritos')->onDelete('cascade');
            $table->foreignId('innovacion_id')->constrained('innovaciones')->onDelete('cascade');
            $table->decimal('monto_comprometido', 12, 2);
            $table->text('mensaje_apoyo')->nullable();
            $table->enum('recompensa_seleccionada', ['ninguna', 'basica', 'intermedia', 'premium'])->default('ninguna');
            $table->unique(['carrito_id', 'innovacion_id']);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('carrito_detalles'); }
};