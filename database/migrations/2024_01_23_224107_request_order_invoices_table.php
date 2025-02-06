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
        Schema::connection('osano')->create('request_order_invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->uuid('request_order_id');
            $table->uuid('purchase_order_id')->nullable();
            $table->enum('payment_status', [0,1,2])->default(0);

            $table->timestamps();

            $table->foreign('request_order_id')
                ->references('id')
                ->on('request_orders')
                ->onDelete('restrict');

            $table->foreign('purchase_order_id')
                ->references('id')
                ->on('purchase_orders')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('osano')->dropIfExists('request_order_invoices');
    }
};
