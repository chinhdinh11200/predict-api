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
        Schema::create('last_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('start_time');
            $table->unsignedBigInteger('end_time');
            $table->tinyInteger('result')->default(\App\Models\LastResult::UP);
            $table->boolean('is_bet_session')->default(true);
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
        Schema::dropIfExists('last_results');
    }
};
