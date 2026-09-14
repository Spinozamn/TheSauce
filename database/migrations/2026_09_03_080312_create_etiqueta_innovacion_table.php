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
        Schema::create('etiqueta_innovacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etiqueta_id')->constrained('etiquetas')->onDelete('cascade');
            $table->foreignId('innovacion_id')->constrained('innovaciones')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['etiqueta_id', 'innovacion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etiqueta_innovacion');
    }
};
