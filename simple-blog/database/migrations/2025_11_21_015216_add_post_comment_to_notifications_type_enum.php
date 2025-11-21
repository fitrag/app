<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Alter the enum to add 'post_comment'
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('post_love', 'comment_reply', 'comment_love', 'post_comment') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove 'post_comment' from enum
        DB::statement("ALTER TABLE notifications MODIFY COLUMN type ENUM('post_love', 'comment_reply', 'comment_love') NOT NULL");
    }
};
