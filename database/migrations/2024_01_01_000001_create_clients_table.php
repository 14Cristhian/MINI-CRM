<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('company');
            $table->enum('status', ['activo', 'inactivo', 'prospecto'])->default('prospecto');
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'user_id']);
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
