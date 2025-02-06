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
        Schema::connection('osano')->create('logistic_addresses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('logistic_id');

            $table->string('name');
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->bigInteger('postal_code')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();

            $table->foreign('logistic_id')
                ->references('id')
                ->on('logistics')
                ->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('osano')->dropIfExists('logistic_addresses');
    }
};
