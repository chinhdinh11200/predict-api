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
        Schema::create('charts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('start_time');
            $table->unsignedBigInteger('end_time');
            $table->decimal('open_price', 15, 8);
            $table->decimal('close_price', 15, 8);
            $table->decimal('low_price', 15, 8);
            $table->decimal('high_price', 15, 8);
            $table->decimal('volume', 15, 8)->default(0);
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
        Schema::dropIfExists('charts');
    }
};
