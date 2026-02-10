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
        Schema::create('love2fa_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('love2fa_id')
                ->constrained('love2fas')
                ->onDelete('cascade');
            $table->string('guessed_name'); // The name they guessed
            $table->boolean('is_correct')->default(false);
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('love2fa_id');
            $table->index('is_correct');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('love2fa_attempts');
    }
};
