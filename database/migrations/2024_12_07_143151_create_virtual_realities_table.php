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
        Schema::create('virtual_realities', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('sub_project_id');
            $table->enum('type', ['send', 'receive']);
            $table->integer('connection_id');
            $table->string('topic');
            $table->integer('pattern_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_realities');
    }
};
