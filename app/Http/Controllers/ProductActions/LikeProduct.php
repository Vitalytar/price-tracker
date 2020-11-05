<?php

namespace App\Http\Controllers\ProductActions;

use App\Http\Controllers\Controller;
use App\Trlprice\Model\LikeProduct as LikeModel;
use App\ProductPrice;
use Illuminate\Http\Request;

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
     * @var ProductPrice
     */
    protected $productPriceModel;

    /**
     * LikeProduct constructor.
     *
     * @param LikeModel    $likeModel
     * @param ProductPrice $productPriceModel
     */
    public function __construct(LikeModel $likeModel, ProductPrice $productPriceModel)
    {
        $this->likeModel = $likeModel;
        $this->productPriceModel = $productPriceModel;
    }

    /**
     * Save liked product by user
     *
     * @param Request $request
     *
     * @return string
     */
    public function saveLikedProduct(Request $request)
    {
        $productId = $request->input('productId');
        $notify = $request->input('notify');

        $this->likeModel->user_id = $request->user()->id;
        $this->likeModel->product_id = $productId;
        $this->likeModel->notify = $notify;
        $this->likeModel->product_price = $this->getLatestProductPrice($productId);

        $this->likeModel->save();

        return 'Saved!';
    }

    /**
     * Get last parsed product price
     *
     * @param $productId
     *
     * @return mixed
     */
    protected function getLatestProductPrice($productId)
    {
        $collection = $this->productPriceModel->where('product_relation_id', $productId)->get();
        $lastProduct = $collection->sortByDesc('id')->first();

        return $lastProduct->product_price;
    }
}
