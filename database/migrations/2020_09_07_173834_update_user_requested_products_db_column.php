<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class UpdateUserRequestedProductsDbColumn
 */
class UpdateUserRequestedProductsDbColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_requested_product', function (Blueprint $table) {
            $table->integer('requested_product_id')->change();
        });
    }
}
