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
        Schema::connection('osano')->create('reimbursement_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->string('code');
            $table->uuid('reimbursement_id');
            $table->decimal('paid_total', 15, 2);
            $table->text('file')->nullable();

            $table->timestamps();

            $table->foreign('reimbursement_id')
                ->references('id')
                ->on('reimbursements')
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
        Schema::connection('osano')->dropIfExists('reimbursement_payments');
    }
};
