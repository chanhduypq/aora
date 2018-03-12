<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $table = 'shipping_methods';
    protected $fillable = ['*'];

    /**
     * @return mixed
     */
    public function getAddPrice()
    {
        return $this->weight_charge + $this->fuel_surcharge;
    }
}
