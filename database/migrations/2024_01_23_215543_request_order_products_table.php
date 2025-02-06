<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('osano')->create('request_order_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('request_order_id');
            $table->uuid('product_id')->nullable();
            $table->bigInteger('qty');
            $table->decimal('price_sale', 15, 2);
            
            $table->timestamps();

            $table->foreign('request_order_id')
                ->references('id')
                ->on('request_orders')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('restrict');
                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('osano')->dropIfExists('request_order_products');
    }
};
