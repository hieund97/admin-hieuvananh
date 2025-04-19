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
        Schema::create('wish', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('wish_message');
            $table->integer('wish_status')->default(0); // 0: not completed, 1: completed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wish');
    }
};
