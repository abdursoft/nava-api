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
        Schema::create('deposit_notifications', function (Blueprint $table) {
            $table->id();
            $table->enum('type',['Deposit','Withdraw']);
            $table->decimal('amount')->default(0);
            $table->longText('message')->nullable();
            $table->enum('status',['Pending','Completed','Canceled'])->nullable('Pending');
            $table->enum('intent',['user','agent'])->nullable('agent');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposit_notifications');
    }
};
