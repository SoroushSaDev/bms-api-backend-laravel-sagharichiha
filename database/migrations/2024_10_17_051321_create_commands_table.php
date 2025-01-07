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
        Schema::create('commands', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('device_id');
            $table->integer('register_id')->nullable();
            $table->string('title');
            $table->enum('type', ['Text', 'JSON', 'Switch', 'SetPoint', 'Selection'])->default('Text');
            $table->string('command');
            $table->string('value')->nullable();
            $table->string('current')->nullable();
            $table->boolean('auto')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commands');
    }
};
