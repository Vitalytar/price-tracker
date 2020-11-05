<?php

namespace App\Trlprice\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

/**
 * Class LikeProduct
 *
 * @package App\Trlprice\Model
 */
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

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Check if user already liked product
     *
     * @param         $productId
     * @param Request $request
     */
    public function doesUserLikedProduct($productId, Request $request)
    {
        $userId = $request->user()->id;
    }
}
