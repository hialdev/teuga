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
        Schema::connection('osano')->create('purchase_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->date('date');
            $table->uuid('request_order_id')->nullable();
            $table->uuid('principal_id');
            $table->uuid('principal_pic_id')->nullable();
            $table->text('description')->nullable();
            $table->uuid('delivery_address_id')->nullable(); // Client Address
            $table->uuid('pickup_address_id')->nullable(); // Principal Address
            
            $table->decimal('total_price', 15, 2)->nullable();
            $table->integer('tax')->nullable();
            $table->decimal('total_price_taxed', 15, 2)->nullable();

            // Pengiriman
            $table->enum('is_handle_logistic', [0,1])->default(0);
            $table->uuid('transport_id')->nullable();

            $table->enum('status', [0,1,2])->default(0); // 0: Pending, 1: Diproses, 2: Selesai
            $table->boolean('generate_invoice')->default(0);

            $table->timestamps();

            $table->foreign('request_order_id')
                ->references('id')
                ->on('request_orders')
                ->onDelete('cascade');

            $table->foreign('principal_id')
                ->references('id')
                ->on('principals')
                ->onDelete('restrict');

            $table->foreign('principal_pic_id')
                ->references('id')
                ->on('principal_pics')
                ->onDelete('restrict');

            $table->foreign('transport_id')
                ->references('id')
                ->on('transports')
                ->onDelete('restrict');

            $table->foreign('delivery_address_id')
                ->references('id')
                ->on('client_addresses')
                ->onDelete('restrict');
            
            $table->foreign('pickup_address_id')
                ->references('id')
                ->on('principal_addresses')
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
        Schema::connection('osano')->dropIfExists('purchase_orders');
    }
};
