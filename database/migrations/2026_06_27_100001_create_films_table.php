<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('films', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('genre');
            $table->year('year')->nullable();
            $table->string('director');
            $table->string('producer')->nullable();
            $table->decimal('budget', 15, 2)->nullable();
            $table->string('status', 50)->default('Development');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('synopsis')->nullable();
            $table->string('poster')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_focus')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('films');
    }
};
