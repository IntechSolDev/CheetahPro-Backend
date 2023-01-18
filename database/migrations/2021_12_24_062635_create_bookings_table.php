<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_uuid')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('provider_id')->unsigned()->nullable();
            $table->text('note')->nullable();
            $table->text('address')->nullable();
            $table->string('booking_time')->nullable();
            $table->string('booking_date')->nullable();
            $table->boolean('is_today')->default(0)->nullable();
            $table->boolean('is_schedule')->default(0)->nullable();
            
            $table->boolean('accepted')->nullable();
            $table->boolean('on_the_way')->nullable();
            $table->boolean('completed')->nullable();
            $table->boolean('rejected')->nullable();
            
            $table->boolean('arrived')->nullable();
            $table->boolean('cancelled')->nullable();
            $table->boolean('progress')->nullable();
            $table->boolean('request_completed')->nullable();
            
            $table->text('rejected_comment')->nullable();
            
            $table->string('total_amount')->nullable();
            $table->string('booking_status')->default('pending')->nullable();
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
        Schema::dropIfExists('bookings');
    }
}
