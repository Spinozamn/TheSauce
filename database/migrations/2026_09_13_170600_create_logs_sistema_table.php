<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('logs_sistema', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->string('accion', 100);
            $table->string('entidad_afectada', 50)->nullable();
            $table->unsignedBigInteger('entidad_id')->nullable();
            $table->json('detalles')->nullable();
            $table->string('ip_origen', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('logs_sistema'); }
};