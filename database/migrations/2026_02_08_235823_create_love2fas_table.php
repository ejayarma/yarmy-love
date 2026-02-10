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
        Schema::create('love2fas', function (Blueprint $table) {
            $table->id();
            $table->string('sender_name'); // The actual sender's name (hidden until guessed)
            $table->string('sender_email');
            $table->string('recipient_name');
            $table->string('recipient_pincode'); // PIN to authenticate recipient
            $table->text('gift_description');
            $table->text('message');
            $table->string('slug')->unique();
            $table->integer('max_attempts')->default(5);
            $table->json('hints')->nullable(); // Hints about who sent it
            $table->integer('hints_viewed')->default(0);
            $table->boolean('is_revealed')->default(false);
            $table->timestamp('revealed_at')->nullable();
            $table->boolean('is_unlocked')->default(false); // Has recipient entered correct PIN?
            $table->timestamp('unlocked_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('slug');
            $table->index('is_revealed');
            $table->index('is_unlocked');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('love2fas');
    }
};
