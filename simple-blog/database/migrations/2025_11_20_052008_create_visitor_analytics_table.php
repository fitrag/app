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
        Schema::create('visitor_analytics', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('device_type', 50)->nullable(); // mobile, tablet, desktop
            $table->string('device_model', 100)->nullable();
            $table->string('browser', 50)->nullable();
            $table->string('browser_version', 20)->nullable();
            $table->string('platform', 50)->nullable(); // OS
            $table->string('platform_version', 20)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->text('referrer')->nullable();
            $table->string('page_url', 500);
            $table->string('page_title', 255)->nullable();
            $table->string('session_id', 100)->nullable();
            $table->integer('visit_duration')->default(0); // in seconds
            $table->timestamps();
            
            $table->index('ip_address');
            $table->index('device_type');
            $table->index('browser');
            $table->index('platform');
            $table->index('session_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitor_analytics');
    }
};
