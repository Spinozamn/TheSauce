<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('lista_deseos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('innovacion_id')->constrained('innovaciones')->onDelete('cascade');
            $table->unique(['usuario_id', 'innovacion_id']);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('lista_deseos'); }
};