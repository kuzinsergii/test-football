<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('league_id')->constrained()->onDelete('cascade');
            $table->foreignId('team_a_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('team_b_id')->constrained('teams')->onDelete('cascade');
            $table->integer('week');
            $table->integer('score_team_a')->nullable();
            $table->integer('score_team_b')->nullable();
            $table->boolean('played')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('games');
    }
}

