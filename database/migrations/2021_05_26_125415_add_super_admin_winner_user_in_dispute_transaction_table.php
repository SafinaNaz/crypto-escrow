<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSuperAdminWinnerUserInDisputeTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dispute_transaction', function (Blueprint $table) {
            $table->bigInteger('winner_user_by_admin')->nullable()->comment('winner by super admin')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dispute_transaction', function (Blueprint $table) {
            //
        });
    }
}
