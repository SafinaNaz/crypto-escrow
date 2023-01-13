<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnnoucementsColumnInSiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->text('site_announcement')->nullable();
            $table->boolean('show_site_announcement')->default(0);
            
            $table->text('seller_announcement')->nullable();
            $table->boolean('show_seller_announcement')->default(0);

            $table->text('buyer_announcement')->nullable();
            $table->boolean('show_buyer_announcement')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('site_settings', function (Blueprint $table) {
            //
        });
    }
}
