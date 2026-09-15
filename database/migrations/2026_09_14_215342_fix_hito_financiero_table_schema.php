<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('hito_financieros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('innovacion_id')->constrained('innovaciones')->onDelete('cascade');
            $table->string('titulo', 150);
            $table->text('descripcion');
            $table->decimal('monto_objetivo', 12, 2);
            $table->date('fecha_limite');
            $table->enum('estado', ['pendiente', 'cumplido', 'vencido'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('hito_financieros');
    }
};
