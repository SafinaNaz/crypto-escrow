<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEtlColumnsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('address2',100)->nullable()->after('address');
            $table->string('dob', 20)->nullable()->after('zipcode');
            $table->boolean('wallet_type')->default(1)->after('dob')->comment('wellet type 1=bitcoin , 2=monero');
            $table->string('btc_address',100)->nullable()->after('wallet_type');
            $table->string('monero_address',100)->nullable()->after('btc_address');
            $table->string('passport')->nullable()->after('monero_address');
            $table->string('nic_front')->nullable()->after('passport');
            $table->string('nic_back')->nullable()->after('nic_front');
            $table->string('driving_lisence')->nullable()->after('nic_back');
            $table->boolean('approved_status')->default(0)->after('driving_lisence')->comment('admin approve status default 0');
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
