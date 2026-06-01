<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone', 20)->nullable();
            $table->string('position')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['email', 'client_id']);
            $table->index(['client_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
