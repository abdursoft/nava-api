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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->longText('message')->nullable();
            $table->longText('file')->nullable();
            $table->enum('file_type',['mp4','webm','mp3','aac','wav','png','jpeg','jpg','webp'])->nullable();
            $table->enum('status',['delivered','sent','seen'])->default('delivered');

            // relation with user table
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->foreign('sender_id')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('receiver_id')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();

            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
