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
        Schema::create('events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('project_id')->nullable(); // nullable if tracking_id dictates tenant
            $table->string('event_id')->unique(); // For Deduplication
            $table->string('event_name'); // PageView, AddToCart, Purchase
            $table->string('user_id')->nullable();
            $table->json('user_data')->nullable(); // browser data, ip, etc.
            $table->timestamp('event_time')->nullable();
            $table->json('custom_data')->nullable(); // JSON payload
            $table->string('source')->default('web');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
