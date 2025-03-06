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
        Schema::connection('accounting')->create('accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('master_account_id');
            $table->uuid('parent_account_id')->nullable();

            $table->string('code');
            $table->string('account_name');
            $table->text('description')->nullable();
            $table->boolean('is_logical')->default(0);
            $table->timestamps();

            $table->foreign('master_account_id')
                ->references('id')
                ->on('master_accounts')
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
        Schema::connection('accounting')->dropIfExists('accounts');
    }
};
