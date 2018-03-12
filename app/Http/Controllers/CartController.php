<?php

namespace App\Http\Controllers;

use App\Rate;
use App\ShippingMethod;
use App\Traits\CartTrait;
use Illuminate\Http\Request;
use Auth;
use App\Classes\DHL;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    use CartTrait;

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeItemFromCart(Request $request)
    {
        if($request->ajax() && $this->removeItem($request->all())) {
            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'error']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeItem(Request $request)
    {
        if($request->ajax() && $this->updateItem($request->all())) {
            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'error']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addToCart(Request $request)
    {
        $data = $request->all();

        if(!empty($data)) {
            $this->addItem($data);

            return redirect()->route('cart');
        }

        return redirect()->back();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function cart()
    {
        $cart = $this->getCart();

        if(!$cart) {
            $total = 0;
            // return redirect()->route('pages.home');
        } else {
            $total = $this->getCartTotal();
        }

        $shipping = ShippingMethod::all();
        $discountTotal = $total * (config('settings.discount') / 100);

        return view('cart.cart', [
            'cart' => $cart,
            'shipping' => $shipping,
            'total' => $total,
            'discountTotal' => $discountTotal,
            'convertPounds' => config('settings.units.pound_eq_gram'),
        ]);
    }
}
