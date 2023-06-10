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
        Schema::create('bot_destination_encuesta', function (Blueprint $table) {
            $table->foreignId('encuesta_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('bot_destination_id')->references('id')->on('bot_destinations')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_destination_encuesta');
    }
};
