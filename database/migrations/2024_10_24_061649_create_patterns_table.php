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
        Schema::create('patterns', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('device_id');
            $table->string('setter')->nullable();
            $table->boolean('use_board_id')->default(0);
            $table->string('beginner')->nullable();
            $table->string('finisher')->nullable();
            $table->string('separator');
            $table->string('connector')->nullable();
            $table->integer('length')->nullable();
            $table->enum('type', ['Json', 'Array', 'Custom'])->default('Custom');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patterns');
    }
};
