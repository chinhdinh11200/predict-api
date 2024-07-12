<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('session_id');
            $table->decimal('amount', 15, 8);
            $table->boolean('is_demo')->default(\App\Models\Bet::REAL_TYPE);
            $table->tinyInteger('bet_type');
            $table->tinyInteger('is_result')->default(\App\Models\Bet::NO_ACTION);
            $table->tinyInteger('result')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->decimal('reward', 15, 8)->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bets');
    }
};
