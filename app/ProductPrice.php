<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ProductPrice
 *
 * @package App
 */
class ProductPrice extends Model
{
    /**
     * @var string
     */
    protected $table = 'product_prices';

    /**
     * @var string
     */
    protected $primaryKey = 'id';
}
