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
        Schema::create('refer_earns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('amount')->default(0);
            $table->date('today');

            // relation with user table
            $table->foreignUuid('host_id')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();
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
        Schema::dropIfExists('refer_earns');
    }
};
