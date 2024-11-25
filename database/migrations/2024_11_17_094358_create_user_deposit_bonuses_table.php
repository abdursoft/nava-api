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
        Schema::create('user_deposit_bonuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // relation with bonus table
            $table->foreignUuid('bonus_id')->references('id')->on('deposit_bonuses')->cascadeOnUpdate()->restrictOnDelete();
            // relation with user table
            $table->foreignUuid('user_id')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_deposit_bonuses');
    }
};