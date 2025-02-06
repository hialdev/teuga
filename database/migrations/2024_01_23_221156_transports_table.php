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
        Schema::connection('osano')->create('transports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date')->nullable();
            $table->string('code');
            $table->string('spk_code');
            $table->string('surjal_code');
            $table->uuid('logistic_id');
            
            $table->decimal('total_price', 15, 2)->nullable();
            $table->integer('tax')->nullable();
            $table->decimal('total_price_taxed', 15, 2)->nullable();

            $table->enum('status', [0,1,2])->default(0); // 0: Pending, 1: Diproses, 2: Selesai
            $table->boolean('generate_invoice')->default(0);

            $table->timestamps();

            $table->foreign('logistic_id')
                ->references('id')
                ->on('logistics')
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
        Schema::connection('osano')->dropIfExists('transports');
    }
};
