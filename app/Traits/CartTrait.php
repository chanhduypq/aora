<?php

namespace App\Traits;

use Illuminate\Support\Facades\Session;
use App\Rate;
use Cache;

trait CartTrait
{
    /**
     * @return bool
     */
    protected function removeCart()
    {
        Session::remove('cart');

        return true;
    }

    /**
     * @param array $item
     * @return bool
     */
    protected function removeItem(array $item)
    {
        if(!$item['id']) {
            return false;
        }

        $basket = $this->getCart();

        if(empty($basket)) {
            return false;
        }

        foreach($basket as $k => $v) {
            if($v['id'] == $item['id']) {
                unset($basket[$k]);
                break;
            }
        }

        $this->removeCart();

        if(!empty($basket)) {
            array_map(function ($item) {
                Session::push('cart', $item);
            }, $basket);
        }

        return true;
    }

    /**
     * @param array $item
     * @return bool
     */
    protected function updateItem(array $item)
    {
        if(empty($item['price']) && !empty($item['shop_price'])) {
            $item['price'] = $item['shop_price'];
        }
        if(!$item['id'] || !$item['price']) {
            return false;
        }

        $basket = $this->getCart();

        foreach($basket as &$v) {
            if($v['id'] == $item['id']) {
                $v['quantity'] = (int) $item['quantity'];
            }
        }

        unset($v);

        $this->removeCart();

        array_map(function($item) {
            Session::push('cart', $item);
        }, $basket);

        return true;
    }

    /**
     * @param array $item
     * @return bool
     */
    protected function addItem(array $item)
    {
        if(!$item['shop_price']) {
            return;
        }
        
        $kichthuoc = $item['shipping_dimension'];
        $kt_parser = explode(' x ', $kichthuoc);
        if(count($kt_parser) == 3) {
            $trongluongdo1 = (float) $kt_parser[0] * 2.54;
            $trongluongdo2 = (float) $kt_parser[1] * 2.54;
            $trongluongdo3 = (float) $kt_parser[2] * 2.54;
            $trongluongdo = ($trongluongdo1 * $trongluongdo2 * $trongluongdo3)/5;
    
            $trongluongcan = (float) $item['weight_gram'];
            $item['weight_gram_max'] = max($trongluongcan, $trongluongdo);
        }

        unset($item['_token']);

        $cart = $this->getCart();
        $_item = null;

        if(!empty($cart)) {
            $_item = array_first($cart, function ($v) use ($item) {
                return $v['id'] == $item['id'];
            });
        }

        if($_item) {
            $this->updateItem($item);
        } else {
            Session::push('cart', $item);
        }

        return true;
    }

    /**
     * @return mixed
     */
    protected function getCart()
    {
        return Session::get('cart');
    }

    /**
     * @return number
     */
    protected function getCartTotal()
    {
        $rateExchange = Cache::remember('rateExchange', 30, function() {
            return Rate::orderBy('id', 'DESC')->first();
        });
        
        $rate = $rateExchange->price;

        $cart = $this->getCart();

        $total = array_sum(array_map(function($item) use ($rate) {
            return round((int) $item['quantity'] * (float) $item['shop_price'] * $rate, 2);
        }, $cart));

        return $total;
    }
}