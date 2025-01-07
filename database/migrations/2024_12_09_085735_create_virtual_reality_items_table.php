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
        Schema::create('virtual_reality_items', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('virtual_reality_id');
            $table->integer('register_id');
            $table->string('key');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('virtual_reality_items');
    }
};
