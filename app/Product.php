<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'style', 'color', 'size', 'conf', 'user_price', 'shop_price', 'order_id', 'quantity', 'image', 'url',
    ];

    /**
     * @return string
     */
    public function getTotalPrice()
    {
        return $this->shop_price * $this->quantity;
    }

    public function getSubTotalPrice($order) {
        $price = $this->getTotalPrice() * $order->rate;
        $weight = ceil($this->quantity * $this->weight_gram / 100);
        $shipping_fee = $weight * ($order->shippingMethod->weight_charge + $order->shippingMethod->fuel_surcharge) + $order->shippingMethod->base_charge;
        $discount = $price * (config('settings.discount') / 100);
        return $price+$shipping_fee-$discount;
    }

    /**
     * @return array|bool
     */
    public function getDimensions()
    {
        if($dimension = $this->shipping_dimension) {
        } elseif(!$dimension = $this->product_dimension) {
            return false;
        }

        $dimension = trim(str_replace('inches', '', $dimension));

        return explode('x', $dimension);
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        if(!empty($this->shipping_weight)) {
            $weight = explode(" ", $this->shipping_weight)[0];
        } elseif(!empty($this->product_weight)) {
            $weight = explode(" ", $this->product_weight)[0];
        } else {
            $weight = 1;
        }

        return $weight;
    }
}
