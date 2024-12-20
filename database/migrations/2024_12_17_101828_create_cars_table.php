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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('code_car')->unique();
            $table->string('image');
            $table->string('name');
            $table->string('year');
            $table->string('rating')->default('0');
            $table->string('about')->nullable();
            $table->bigInteger('price')->default(0);
            $table->bigInteger('quantity')->default(1);
            $table->string('feature1');
            $table->string('feature2')->nullable();
            $table->string('feature3')->nullable();
            $table->string('feature4')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
