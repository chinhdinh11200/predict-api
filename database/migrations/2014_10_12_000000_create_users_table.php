<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('refcode')->nullable();
            $table->decimal('real_balance', 15, 8)->default(0);
            $table->decimal('virtual_balance', 15, 8)->default(1000);
            $table->decimal('usdt_balance', 15, 8)->default(0);
            $table->unsignedSmallInteger('level')->default(0);
            $table->unsignedSmallInteger('status')->default(User::STATUS_ACTIVE)->comment('0: inactive, 1: active');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
