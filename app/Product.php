<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 *
 * @package App
 */
class Product extends Model
{
    /**
     * @var string
     */
    protected $table = 'product_details';

    /**
     * @var string
     */
    protected $primaryKey = 'id';
}
