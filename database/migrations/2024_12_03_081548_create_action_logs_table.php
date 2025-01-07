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
        Schema::create('action_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('action_loggable_id');
            $table->string('action_loggable_type');
            $table->string('field')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->enum('action', ['created', 'updated', 'deleted'])->default('updated');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_logs');
    }
};
