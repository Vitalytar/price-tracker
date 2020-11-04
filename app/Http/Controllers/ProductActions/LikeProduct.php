<?php

namespace App\Http\Controllers\ProductActions;

use App\Http\Controllers\Controller;
use App\Trlprice\Model\LikeProduct as LikeModel;

/**
 * Class LikeProduct
 *
 * @package App\Http\Controllers\ProductActions
 */
class LikeProduct extends Controller
{
    /**
     * @var LikeModel
     */
    protected $likeModel;

    /**
     * LikeProduct constructor.
     *
     * @param LikeModel $likeModel
     */
    public function __construct(LikeModel $likeModel)
    {
        $this->likeModel = $likeModel;
    }

    /**
     * Save liked product by user
     *
     * @param $productId
     * @param $notify
     *
     * @return string
     */
    public function saveLikedProduct(\Illuminate\Http\Request $request)
    {
        $productId = $request->input('productId');
        $notify = $request->input('notify');

        $z = '';

        return 'Saved!';
    }
}
