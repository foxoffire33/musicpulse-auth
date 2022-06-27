<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use \Illuminate\Support\Str;

class DeviceToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devices',function(Blueprint $blueprint) {
            $blueprint->uuid('id')->primary();
            $blueprint->string('device_name');
            $blueprint->string('device_token')->index();
            $blueprint->string('device_os');
            $blueprint->double('os_version');
            $blueprint->string('app_version');
            $blueprint->uuid('created_by')->nullable();
            $blueprint->uuid('updated_by')->nullable();
            $blueprint->uuid('deleted_by')->nullable();
            $blueprint->timestamps();
            $blueprint->softDeletes();

            $blueprint->foreign('created_by')->references('id')->on('users');
            $blueprint->foreign('updated_by')->references('id')->on('users');
            $blueprint->foreign('deleted_by')->references('id')->on('users');
        });




        Schema::create('device_user',function(Blueprint $blueprint) {
            $blueprint->uuid('user_id');
            $blueprint->uuid('device_id');
            $blueprint->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $blueprint->foreign('device_id')->references('id')->on('devices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_user');
        Schema::dropIfExists('devices');
    }
}
