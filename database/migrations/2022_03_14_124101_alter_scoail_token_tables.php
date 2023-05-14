<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterScoailTokenTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('social_tokens', function (Blueprint $table) {
            $table->longText('refresh_token')->after('token');
        });
           
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('social_tokens', function($table) {
            $table->dropColumn('refresh_token');
        });
    }
}
