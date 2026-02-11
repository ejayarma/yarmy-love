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
        Schema::create('valentines', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('sender_email');
            $table->string('sender_name');
            $table->string('crush_name');
            $table->text('message');
            $table->boolean('force_yes')->default(false);
            $table->string('pincode')->nullable();
            $table->enum('response', ['yes', 'no', 'maybe'])->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('valentines');
    }
};
