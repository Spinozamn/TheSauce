<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('carritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->enum('estado', ['activo', 'finalizado', 'abandonado'])->default('activo');
            $table->unique('usuario_id');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('carritos'); }
};