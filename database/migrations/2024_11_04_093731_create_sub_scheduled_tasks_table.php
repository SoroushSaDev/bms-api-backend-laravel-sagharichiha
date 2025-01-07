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
        Schema::create('sub_scheduled_tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('scheduled_task_id');
            $table->string('title');
            $table->enum('type', ['command', 'log']);
            $table->enum('run_time', ['start', 'hourly', 'daily', 'weekly', 'monthly', 'end', 'count', 'register', 'custom'])->default('start');
            $table->boolean('priority')->default(false);
            $table->boolean('safe')->default(false);
            $table->enum('status', ['idle', 'done', 'failed'])->default('idle');
            $table->integer('device_id');
            $table->integer('register_id')->nullable();
            $table->integer('command_id')->nullable();
            $table->string('value')->nullable();
            $table->integer('count')->default(1);
            $table->enum('custom_run_time_type', ['off', 'second', 'minute', 'hour', 'day', 'week', 'month'])->default('off');
            $table->integer('custom_run_time_value')->nullable();
            $table->boolean('checksum')->default(false);
            $table->integer('checksum_register_id')->nullable();
            $table->string('checksum_register_operand')->nullable();
            $table->string('checksum_register_value')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('ran')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_scheduled_tasks');
    }
};
