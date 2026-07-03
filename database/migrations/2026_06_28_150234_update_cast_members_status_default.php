<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('cast_members')->whereNull('status')->orWhere('status', '')->update(['status' => 'Draft']);
        Schema::table('cast_members', function (Blueprint $table) {
            $table->string('status', 50)->default('Draft')->change();
        });
    }

    public function down(): void
    {
        DB::table('cast_members')->where('status', 'Draft')->update(['status' => 'Aktif']);
        Schema::table('cast_members', function (Blueprint $table) {
            $table->string('status', 50)->default('Aktif')->change();
        });
    }
};
