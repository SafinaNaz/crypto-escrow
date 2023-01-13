<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('firstname', 30);
            $table->string('lastname', 30);

            $table->string('email')->unique();
            $table->string('password', 255);

            $table->string('mobile', 20);
            $table->string('address', 100);
            $table->string('city', 20);
            $table->string('state', 20);
            $table->string('country', 20);
            $table->string('zipcode', 20);
            $table->tinyInteger('is_active')->default(0)->comment('0=inactive;1=active');
            $table->dateTime('last_login_on');
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
        Schema::dropIfExists('admins');
    }
}
