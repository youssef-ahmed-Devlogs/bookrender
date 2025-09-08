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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->nullable();
            $table->string('retinalogo')->nullable();
            $table->string('favicon')->nullable();
    
            // Font sizes
            $table->integer('font_h1')->nullable();
            $table->integer('font_h2')->nullable();
            $table->integer('font_h3')->nullable();
            $table->integer('font_h4')->nullable();
            $table->integer('font_h5')->nullable();
            $table->integer('font_paragraph')->nullable();
    
            // Colors
            $table->string('body')->nullable();
            $table->string('heading')->nullable();
            $table->string('para')->nullable();
            $table->string('button')->nullable();
    
            // Title
            $table->string('title')->nullable();
            $table->string('fontfamily')->nullable();
    
            $table->timestamps();
        });
    }
    
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
