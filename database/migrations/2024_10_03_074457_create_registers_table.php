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
        Schema::create('registers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->integer('device_id');
            $table->integer('parent_id')->default(0);
            $table->string('title');
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->string('unit')->nullable();
            $table->string('scale')->nullable();
            $table->enum('type', ['none', 'bool', 'int'])->default('none');
            $table->enum('permission', ['ReadOnly', 'ReadWrite'])->default('ReadOnly');
            $table->integer('pattern_id')->nullable();
            $table->string('border_color')->nullable();
            $table->string('background_color')->nullable();
            $table->dateTime('connected_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registers');
    }
};
