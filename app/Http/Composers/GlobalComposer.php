<?php

namespace App\Http\Composers;

use App\Rate;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Cache;

/**
 * Class GlobalComposer.
 */
class GlobalComposer
{
    /**
     * Bind data to the view.
     *
     * @param View $view
     *
     * @return void
     */
    public function compose(View $view)
    {
        $cart = Session::get('cart');

        $rateExchange = Cache::remember('rateExchange', 30, function() {
            return Rate::orderBy('id', 'DESC')->first();
        });

        $view
            ->with([
                'cart_count' => count($cart),
                'rate' => $rateExchange->price,
                'siteCurrency' => config('settings.currencies.site_currency'),
                'shopCurrency' => config('settings.currencies.shop_currency'),
                'discount' => config('settings.discount'),
            ]);
    }
}
