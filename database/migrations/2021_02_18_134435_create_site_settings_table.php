<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiteSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            $table->string('site_logo')->nullable();
			$table->string('site_name')->nullable();
			$table->string('site_title')->nullable();
			$table->text('site_keywords')->nullable();
			$table->text('site_description')->nullable();
			$table->string('site_email')->nullable();
			$table->string('inquiry_email')->nullable();
			$table->string('site_phone')->nullable();
			$table->string('site_mobile')->nullable();
			$table->text('site_address')->nullable();

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
        Schema::dropIfExists('site_settings');
    }
}
