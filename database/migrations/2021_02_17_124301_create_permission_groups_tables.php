<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionGroupsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->boolean('status')->default(1)->comment("Active = 1, Inactive=0");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_groups');
    }
}
