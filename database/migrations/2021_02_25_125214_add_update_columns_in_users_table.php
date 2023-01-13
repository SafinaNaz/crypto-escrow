<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdateColumnsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            
            $table->renameColumn('name', 'firstname')->nullable();
            $table->boolean('user_type')->default(1)->after('id')->comment('1=seller;2=customer');
            $table->string('lastname')->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('city', 20)->nullable();
            $table->string('state', 20)->nullable();
            $table->string('country', 20)->nullable();
            $table->string('zipcode', 20)->nullable();
            $table->boolean('is_active')->default(0)->comment('0=inactive;1=active');
            $table->dateTime('last_login_on');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
