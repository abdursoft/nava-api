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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bonus_id')->nullable();
            $table->decimal('payable_amount', 15, 2)->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->string('intent')->nullable();
            $table->enum('status',['Pending','Completed','Failed','Hold'])->default('Pending');
            $table->enum('pay_intent',['DEBIT','CREDIT']);
            $table->string('payment',300)->default('bkash');
            $table->string('wallet',300)->nullable();

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
        Schema::dropIfExists('payment_transactions');
    }
};
