<?php

namespace App\Http\Controllers\ProductActions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UserProductRequest;

/**
 * Class DeleteRequestedProduct
 *
 * @package App\Http\Controllers\ProductActions
 */
class DeleteRequestedProduct extends Controller
{
    /**
     * @var UserProductRequest
     */
    protected $userRequestedProduct;

    /**
     * DeleteRequestedProduct constructor.
     *
     * @param UserProductRequest $userProductRequest
     */
    public function __construct(UserProductRequest $userProductRequest)
    {
        $this->userRequestedProduct = $userProductRequest;
    }

    /**
     * Unassign user requested product from user to global user_id scope - 0
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteRequestedProduct(Request $request)
    {
        $userId = $request->user()->id;
        $productId = $request->input('requested_product_id');
        $requestedProduct = $this->userRequestedProduct->where([
            ['user_id', '=', $userId],
            ['requested_product_id', '=', $productId]
        ])->get();

        foreach ($requestedProduct as $product) {
            $product->user_id = 0;
            $product->save();
        }

        return redirect()->back()->with([
            'status' => __('Produkts tika veiksmīgi izdzēsts no nesen pieprasītajiem produktiem!')
        ]);
    }
}
