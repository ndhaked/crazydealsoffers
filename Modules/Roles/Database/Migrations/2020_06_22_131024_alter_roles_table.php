<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
             $table->string('slug')->after('id');
             $table->string('display_name')->nullable()->after('guard_name');
             $table->string('description')->nullable()->after('display_name');
             $table->boolean('status')->default(1)->comment("Active = 1, Inactive=0")->after('description');
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
        //
    }
}
