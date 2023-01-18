<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->bigInteger('main_service_id')->unsigned()->nullable();
            $table->text('about')->nullable();
            $table->string('post_code')->nullable();
            $table->string('email')->unique();
            $table->string('password')->nullable();
            $table->string('image')->nullable();
            $table->string('contactno')->nullable();
            $table->text('address')->nullable();
            $table->string('street')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('type')->nullable();
            $table->string('current_type')->nullable();
            $table->integer('main_service')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->longText('fcm_token')->nullable();
            $table->integer('avg_rating')->nullable();
            $table->foreign('main_service_id')->references('id')->on('services')->onDelete('cascade');
            $table->string('stripe_connect_id')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
