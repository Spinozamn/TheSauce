<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('cuentas_sociales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->string('proveedor');
            $table->string('id_proveedor');
            $table->string('token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->timestamp('expira_en')->nullable();
            $table->unique(['usuario_id', 'proveedor']);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('cuentas_sociales'); }
};