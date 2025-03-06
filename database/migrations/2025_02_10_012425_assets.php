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
        Schema::connection('accounting')->create('assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('account_id')->nullable();
            $table->date('date')->default(Carbon::now());
            $table->string('name');
            $table->text('image')->nullable();
            $table->text('description')->nullable();

            // Depreciation
            $table->decimal('purchase_price', 15, 2);
            $table->decimal('residu_price', 15, 2);
            $table->decimal('useful_life', 5, 2)->nullable();

            //Appreciation
            $table->boolean('is_appreciating')->default(0);
            $table->decimal('appreciation_rate', 5, 2)->nullable();
            
            $table->timestamps();

            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('accounting')->dropIfExists('assets');
    }
};
