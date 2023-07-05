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
        Schema::create('menu_exercises', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('menu_id');
            $table->foreign('menu_id')->references('id')->on('menus');
            $table->unsignedInteger('exercise_id');
            $table->foreign('exercise_id')->references('id')->on('exercises');
            $table->integer('order');
            $table->integer('planned_sets');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_exercises');
    }
};