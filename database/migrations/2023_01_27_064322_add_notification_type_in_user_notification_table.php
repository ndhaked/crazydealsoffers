<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationTypeInUserNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_notifications', function (Blueprint $table) {
             $table->enum('notification_type',['product','comment','reply'])->default('product')->after('product_id');
             $table->bigInteger('comment_id')->unsigned()->default(0)->after('notification_type');
             $table->boolean('is_read')->default(0)->after('comment_id');
             $table->timestamp('is_read_date')->nullable()->after('is_read');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            //
        });
    }
}
