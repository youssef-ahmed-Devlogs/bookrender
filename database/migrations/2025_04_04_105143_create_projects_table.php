<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('image')->nullable();
            $table->string('author');
            $table->string('title');
            $table->string('second_title');
            $table->string('description');
            $table->string('treem_size')->nullable();
            $table->string('page')->nullable();
            $table->string('format')->nullable();
            $table->string('bleed_file')->nullable();
            $table->string('category')->nullable();
            $table->string('chapter')->nullable();
            $table->string('text_style')->nullable();
            $table->string('font_size')->nullable();
            $table->string('add_page_num')->nullable();
            $table->string('book_intro')->nullable();
            $table->string('copyright_page')->nullable();
            $table->string('table_of_contents')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
