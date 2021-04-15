<?php

declare(strict_types=1);

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Class UserAccount
 *
 * @package App\View\Components
 */
class UserAccount extends Component
{
    /**
     * @var int
     */
    public $parsedTimes;

    /**
     * @var string
     */
    public $userName;

    /**
     * @var string
     */
    public $userEmail;

    /**
     * @var string
     */
    public $accountCreationTime;

    /**
     * @return Closure|Application|Htmlable|Factory|View|string
     */
    public function render()
    {
        $this->setUserRequestedProducts();
        $this->setUserMainData();

        return view('components.user-account');
    }

    /**
     * @returns void
     */
    public function setUserMainData(): void
    {
        $this->userName = Auth::user()->name;
        $this->userEmail = Auth::user()->email;
        $this->accountCreationTime = Auth::user()->created_at;
    }

    /**
     * @returns void
     */
    public function setUserRequestedProducts(): void
    {
        $products = DB::table('user_requested_product')
            ->join('product_details', 'user_requested_product.requested_product_id', '=', 'product_details.id')
            ->select(
                'user_requested_product.requested_product_id', 'user_requested_product.created_at',
                'product_details.product_name', 'product_details.product_url', 'product_details.product_image_url',
                'product_details.created_at', 'product_details.id', 'product_details.source_web'
            )
            ->where('user_requested_product.user_id', '=', Auth::user()->id)
            ->groupBy('user_requested_product.requested_product_id')
            ->orderByDesc('user_requested_product.created_at')
            ->get();

        $this->parsedTimes = count($products);
    }
}
