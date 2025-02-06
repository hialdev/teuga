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
        Schema::connection('osano')->create('request_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->date('date');
            $table->uuid('client_id');
            $table->uuid('client_pic_id')->nullable();
            $table->string('no_refrence')->nullable();
            $table->text('attachment')->nullable();
            $table->text('description')->nullable();
            
            $table->decimal('total_price', 15, 2)->nullable();
            $table->integer('tax')->nullable();
            $table->decimal('total_price_taxed', 15, 2)->nullable();

            $table->enum('status', [0,1,2])->default(0); // 0: Menunggu Diproses, 1: Diproses, 2: Selesai
            $table->boolean('generate_invoice')->default(0);

            $table->timestamps();

            $table->foreign('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('restrict');
            
            $table->foreign('client_pic_id')
                ->references('id')
                ->on('client_pics')
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
        Schema::connection('osano')->dropIfExists('request_orders');
    }
};
