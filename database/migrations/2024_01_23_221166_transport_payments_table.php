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
        Schema::connection('osano')->create('transport_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->string('code');
            $table->uuid('transport_invoice_id');
            $table->decimal('paid_total', 15, 2);
            $table->text('file')->nullable();

            $table->timestamps();

            $table->foreign('transport_invoice_id')
                ->references('id')
                ->on('transport_invoices')
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
        Schema::connection('osano')->dropIfExists('transport_payments');
    }
};
