<?php

use App\Models\TransactionDetail;
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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('tx', 255)->nullable();
            $table->string('username', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->decimal('amount', 15, 8)->default(0);
            $table->decimal('fee', 15, 8)->default(0);
            $table->text('note')->nullable();
            $table->unsignedSmallInteger('type')->nullable();
            $table->unsignedSmallInteger('status')->default(TransactionDetail::TRANSACTION_STATUS_PENDING);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_details');
    }
};
