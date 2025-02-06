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
        Schema::connection('osano')->create('reimbursements', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('date');
            $table->string('code');
            $table->string('name');
            $table->text('description')->nullable();

            $table->decimal('total_spent', 15, 2)->nullable();

            $table->enum('payment_status', [0,1,2])->default(0);
                //Jika belum ada pembayaran reimburse maka 0 (Diajukan)
                //Jika ada pembayaran namun belum memenuhi total_spent 1 (Bayar Bertahap)
                //Jika memenuhi total_spent 2 (Lunas)

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('osano')->dropIfExists('reimbursements');
    }
};
