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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained();
            $table->foreignId('season_id')->constrained();
            $table->foreignId('competition_id')->constrained();
            $table->foreignId('home_team_id')->constrained('teams');
            $table->foreignId('away_team_id')->constrained('teams');
            $table->dateTime('utc_date');
            $table->string('status');
            $table->string('minute')->nullable();
            $table->unsignedInteger('injury_time')->nullable();
            $table->string('venue')->nullable();
            $table->unsignedInteger('matchday');
            $table->string('stage')->nullable();
            $table->dateTime('last_update');

            // Campos do score
            $table->string('winner')->nullable();
            $table->string('duration');
            $table->integer('home_score_full_time')->nullable();
            $table->integer('away_score_full_time')->nullable();
            $table->integer('home_score_half_time')->nullable();
            $table->integer('away_score_half_time')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
