<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('titulo', 150);
            $table->text('mensaje');
            $table->enum('tipo', ['info', 'exito', 'advertencia', 'error'])->default('info');
            $table->string('entidad_relacionada', 50)->nullable();
            $table->unsignedBigInteger('entidad_id')->nullable();
            $table->string('url_accion')->nullable();
            $table->boolean('leido')->default(false);
            $table->timestamp('fecha_leido')->nullable();
            $table->timestamp('fecha_notificacion')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('notificaciones'); }
};