<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('novel_id');
            $table->string('title');
            $table->text('content');
            $table->integer('chapter_number');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
            
            $table->foreign('novel_id')->references('id')->on('novels')->onDelete('cascade');
            $table->index(['novel_id', 'chapter_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('chapters');
    }
};