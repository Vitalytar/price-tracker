<?php

namespace App\Trlprice\Model;

use Illuminate\Database\Eloquent\Model;

class LikeProduct extends Model
{
    /**
     * @var string
     */
    protected $table = 'liked_products';

    /**
     * @var string
     */
    protected $primaryKey = 'id';
}
