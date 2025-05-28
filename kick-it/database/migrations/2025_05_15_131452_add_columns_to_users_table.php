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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique();
            $table->string('avatar_url')->nullable();
            $table->integer('full_score')->default(0);
            $table->integer('level')->default(1);
            $table->boolean('is_online')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('avatar_url');
            $table->dropColumn('full_score');
            $table->dropColumn('level');
            $table->dropColumn('is_online');
        });
    }
};
