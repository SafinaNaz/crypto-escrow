<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEncryptedColumnInMessagesEscrowProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->binary('message')->change();
        });
        Schema::table('escrow_products', function (Blueprint $table) {
            $table->binary('encrypted_text')->change();
        });
    }

    
}
