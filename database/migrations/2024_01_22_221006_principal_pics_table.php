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
        Schema::connection('osano')->create('principal_pics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('principal_id');
            $table->uuid('parent_pic_id')->nullable();

            $table->text('image')->nullable();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();

            $table->foreign('principal_id')
                ->references('id')
                ->on('principals')
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
        Schema::connection('osano')->dropIfExists('principal_pics');
    }
};
