<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('comentarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('innovacion_id')->constrained('innovaciones')->onDelete('cascade');
            $table->foreignId('comentario_padre_id')->nullable()->constrained('comentarios')->onDelete('cascade');
            $table->text('contenido');
            $table->boolean('es_respuesta')->default(false);
            $table->timestamp('fecha_comentario')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('comentarios'); }
};