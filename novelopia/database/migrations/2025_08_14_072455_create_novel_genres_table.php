<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('novel_genres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('novel_id');
            $table->string('genre');
            $table->timestamps();
            
            $table->foreign('novel_id')->references('id')->on('novels')->onDelete('cascade');
            $table->index(['novel_id', 'genre']);
            $table->unique(['novel_id', 'genre']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('novel_genres');
    }
};