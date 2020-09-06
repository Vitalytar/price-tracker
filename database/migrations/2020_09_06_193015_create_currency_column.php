<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateCurrencyColumn
 */
class CreateCurrencyColumn extends Migration
{
    /**
     * Create currency column
     */
    public function up()
    {
        Schema::table('product_prices', function (Blueprint $table) {
            $table->string('currency')->default('€');
        });
    }
}
