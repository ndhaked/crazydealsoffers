<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertiseAffiliatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertise_affiliated', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('banner_image')->nullable();
            $table->longText('banner_description')->nullable();
            $table->string('image_1')->nullable();
            $table->longText('description_1')->nullable();
            $table->string('image_2')->nullable();
            $table->longText('description_2')->nullable();
            $table->string('description')->nullable();
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('advertise_affiliated');
    }
}
