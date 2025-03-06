<?php

use Carbon\Carbon;
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
        Schema::connection('accounting')->create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('transaction_period_id');
            $table->string('code');

            $table->date('date')->default(Carbon::now());
            $table->boolean('is_closed')->default(0);
            $table->boolean('is_generated')->default(0);
            $table->text('description')->nullable();

            $table->timestamps();

            $table->foreign('transaction_period_id')
                  ->references('id')
                  ->on('transaction_periods')
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
        Schema::connection('accounting')->dropIfExists('transactions');
    }
};
