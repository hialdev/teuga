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
        Schema::connection('accounting')->create('master_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->enum('type', ['assets', 'liabilities', 'equity', 'revenue', 'expenses']);
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
        Schema::connection('accounting')->dropIfExists('master_accounts');
    }
};
