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
        Schema::create('deposit_bonuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('game',300);
            $table->decimal('amount');
            $table->string('message',300);
            $table->decimal('minimum');
            $table->integer('limit')->default(0);
            $table->decimal('turnover');
            $table->date('start_date');
            $table->date('end_date');
            $table->longText('image')->nullable();
            $table->longText('description')->nullable();
            $table->enum('status',['active','inactive']);

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_bonuses');
    }
};
