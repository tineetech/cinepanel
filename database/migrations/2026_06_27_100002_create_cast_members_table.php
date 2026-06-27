<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cast_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('film_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('character_name');
            $table->string('origin')->nullable();
            $table->string('role_type', 50)->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->string('phone')->nullable();
            $table->string('image')->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 50)->default('Aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cast_members');
    }
};
