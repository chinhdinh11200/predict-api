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
        Schema::create('lucky_wheels', function (Blueprint $table) {
            $table->id();
            $table->string('name_vi', 255)->nullable();
            $table->string('name_en', 255)->nullable();
            $table->string('image_url', 255)->nullable();
            $table->integer('slice_quantity')->nullable()->comment('Số lượng ô trên vòng quay');
            $table->integer('prize_quantity')->nullable()->comment('Số lượng giải thưởng ô');
            $table->decimal('winning_probability', 4, 2)->nullable()->comment('Tỷ lệ xác xuất trúng thưởng');
            $table->unsignedSmallInteger('reward')->default(0)->comment('Giá trị giải thưởng');
            $table->unsignedSmallInteger('spin_again')->default(0)->comment('Có được quay lại không');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lucky_wheels');
    }
};
