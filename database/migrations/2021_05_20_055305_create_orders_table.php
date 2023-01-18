<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('provider_id')->unsigned()->nullable();
            $table->bigInteger('main_service_id')->unsigned()->nullable();
            $table->bigInteger('sub_service_id')->unsigned()->nullable();
            $table->string('order_date')->nullable();
            $table->string('hours')->nullable();
            $table->string('amount')->nullable();
            $table->text('address')->nullable();
            $table->boolean('accepted')->nullable();
            $table->boolean('rejected')->nullable();
            $table->text('rejected_comment')->nullable();
            $table->boolean('on_the_way')->nullable();
            $table->boolean('new')->default(1)->nullable();
            $table->boolean('completed')->nullable();
            $table->string('current_status')->default('new')->nullable();
            $table->foreign('sub_service_id')->references('id')->on('sub_services')->onDelete('cascade');
            $table->foreign('main_service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('provider_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
}
