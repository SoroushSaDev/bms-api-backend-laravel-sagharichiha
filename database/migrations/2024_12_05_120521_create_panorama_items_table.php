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
        Schema::create('panorama_items', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('panorama_id');
            $table->integer('form_id');
            $table->string('title');
            $table->integer('x');
            $table->integer('y');
            $table->integer('z')->nullable();
            $table->integer('from')->nullable();
            $table->integer('to')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panorama_items');
    }
};
