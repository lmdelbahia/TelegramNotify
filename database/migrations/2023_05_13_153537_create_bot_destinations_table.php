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
        Schema::create('bot_destinations', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->foreignUuid('bot_id')->references('id')->on('bots')->cascadeOnDelete();
            $table->string('name', 50);
            $table->string('identifier');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_destinations');
    }
};
