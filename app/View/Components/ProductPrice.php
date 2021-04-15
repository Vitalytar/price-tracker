<?php

declare(strict_types=1);

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use Illuminate\View\View;
use App\ProductPrice as ProductPriceModel;

/**
 * Class ProductPrice
 *
 * @package App\View\Components
 */
class ProductPrice extends Component
{
    public $productId;

    /**
     * @var ProductPrice
     */
    protected $productPrice;

    /**
     * ProductPrice constructor.
     *
     * @param                   $productId
     * @param ProductPriceModel $productPrice
     */
    public function __construct($productId, ProductPriceModel $productPrice)
    {
        $this->productId = $productId;
        $this->productPrice = $productPrice;
    }

    /**
     * @return Closure|Htmlable|View|string|void
     */
    public function render()
    {
        $item = current(
            current(
                DB::table('product_prices')
                    ->select('id', 'product_price', 'currency')
                    ->where('product_relation_id', '=', $this->productId)
                    ->orderBy('id', 'desc')
                    ->get()
            )
        );

        if ($item) {
            $this->productId = $item->product_price . $item->currency;
        }

        return view('components.product-price');
    }
}
