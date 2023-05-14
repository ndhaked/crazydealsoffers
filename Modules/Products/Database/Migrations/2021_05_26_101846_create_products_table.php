<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('coupon_code')->nullable();
            $table->string('image')->default('noimage.jpg');
            $table->double('price', 10,2)->nullable();
            $table->double('off_on_product', 10,2)->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->string('item_purchase_link')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('delete_status')->default(0);
            $table->boolean('deal_of_the_day')->default(0);
            $table->enum('status',['active','expired'])->default('active');  
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
        Schema::dropIfExists('products');
    }
}
