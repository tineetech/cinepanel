<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shot_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('film_id')->constrained()->cascadeOnDelete();
            $table->string('scene');
            $table->string('shot_order')->nullable();
            $table->string('shot_description');
            $table->string('camera_type');
            $table->string('camera_movement')->nullable();
            $table->string('estimated_duration')->nullable();
            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('cast_id')->nullable()->constrained('cast_members')->nullOnDelete();
            $table->json('sound')->nullable();
            $table->string('shoot_time')->nullable();
            $table->string('status', 50)->default('Belum');
            $table->text('director_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shot_lists');
    }
};
