<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('contribuciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('restrict');
            $table->foreignId('innovacion_id')->constrained('innovaciones')->onDelete('restrict');
            $table->decimal('monto', 12, 2);
            $table->enum('metodo_pago', ['tarjeta', 'paypal', 'transferencia', 'oxxo'])->default('tarjeta');
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada', 'reembolsada'])->default('pendiente');
            $table->string('referencia_transaccion')->nullable();
            $table->timestamp('fecha_contribucion')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('contribuciones'); }
};