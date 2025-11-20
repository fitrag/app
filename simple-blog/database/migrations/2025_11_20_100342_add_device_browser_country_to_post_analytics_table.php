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
        Schema::table('post_analytics', function (Blueprint $table) {
            $table->string('device_type', 50)->nullable()->after('user_agent'); // desktop, mobile, tablet
            $table->string('browser', 100)->nullable()->after('device_type');
            $table->string('country', 100)->nullable()->after('browser');
            $table->string('country_code', 2)->nullable()->after('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_analytics', function (Blueprint $table) {
            $table->dropColumn(['device_type', 'browser', 'country', 'country_code']);
        });
    }
};
