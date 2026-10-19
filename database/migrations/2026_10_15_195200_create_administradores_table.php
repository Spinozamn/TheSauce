<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('administradores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('usuario', 50)->unique();
            $table->string('email', 150)->unique()->nullable();
            $table->string('password');
            $table->boolean('activo')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('administradores');
    }
};