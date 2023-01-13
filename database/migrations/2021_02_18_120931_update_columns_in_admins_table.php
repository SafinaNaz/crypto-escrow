<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnsInAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {

            $table->string('mobile', 20)->nullable()->change();
            $table->string('address', 100)->nullable()->change();
            $table->string('city', 20)->nullable()->change();
            $table->string('state', 20)->nullable()->change();
            $table->string('country', 20)->nullable()->change();
            $table->string('zipcode', 20)->nullable()->change();
            $table->boolean('is_active')->default(0)->comment('0=inactive;1=active')->change();
            $table->dateTime('last_login_on')->nullable()->change();
        });
    }
}
