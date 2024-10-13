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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable();
            $table->string('name');
            $table->string('type')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->text('description')->nullable();
            $table->string('lan')->nullable();
            $table->string('wifi')->nullable();
            $table->integer('parent_id')->default(0);
            $table->integer('project_id')->default(0);
            $table->string('mqtt_topic');
            $table->string('server_id')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
