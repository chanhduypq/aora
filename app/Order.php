<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Mail;
use DB;
use App\ShippingMethod;

class Order extends Model
{
    use Notifiable;

    CONST ORDER_UNPAID = 0;
    CONST ORDER_CANCEL = 2;
    CONST ORDER_NEW = 1;
    CONST ORDER_LEAVING_USA = 3;
    CONST ORDER_ARRIVED_SG = 5;
    CONST ORDER_SHIPPING = 6;
    CONST ORDER_COMPLETE = 4;
    CONST ORDER_REFUND = 7;
    CONST ORDER_PROCESSING = 100;

    public $dates = ['created_at'];
    protected $fillable = ['*'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function marketplace()
    {
        return $this->belongsTo('App\Marketplace');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippingMethod()
    {
        return $this->belongsTo('App\ShippingMethod');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function billingAddress()
    {
        return $this->belongsTo('App\BillingAddress');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shippingAddress()
    {
        return $this->belongsTo('App\ShippingAddress');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany('App\Product');
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeByWord($query, $value)
    {
        if($value) {
            $query->whereHas('products', function($query) use($value) {
                $query->where('title', 'like', "%$value%");
            });
        }
    }
    public function scopeByName($query, $value)
    {
        if($value) {
            $query->whereHas('user', function($query) use($value) {
                $query->where('name', 'like', "%$value%");
            });
        }
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeById($query, $value)
    {
        if($value) {
            return $query->where('id', $value);
        }
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeByPaypalToken($query, $value)
    {
        if($value) {
            return $query->where('paypal_token', $value);
        }
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeByPaypalId($query, $value)
    {
        if($value) {
            return $query->where('paypal_id', $value);
        }
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeByPaypalStatus($query, $value)
    {
        if($value) {
            return $query->where('paypal_status', $value);
        }
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeByStatus($query, $value)
    {
        if(!is_null($value)) {
            return $query->where('status', $value);
        }
    }

    /**
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeByMarketplace($query, $value)
    {
        if($value) {
            return $query->where('marketplace_id', $value);
        }
    }

    /**
     * @param array $data
     * @param array $cart
     * @return bool
     */
    public function create(array $data, array $cart)
    {
        $shipAddress = new ShippingAddress();

        if(!$shipping = $shipAddress->create($data)) {
            return false;
        }

        $billAddress = new BillingAddress();

        if(!$billing = $billAddress->create($data)) {
            return false;
        }

        $this->rate = $data['rate'];
        $this->discount = $data['discount'];
        $this->payment_type = $data['payment'];
        $this->billing_as_shipping = $billing == 'shipping';
        $this->billing_address_id = $billing != 'shipping' ? $billing : null;
        $this->shipping_address_id = $shipping;
        $this->shipping_total = $data['shipping_total'];
        $this->shipping_method_id = $data['shipping_method'];
        $this->user_id = Auth::user()->id;
        $this->status = self::ORDER_UNPAID;
        $this->payment_hash = Hash::make(time());
        $this->save();

        $shipping_method = ShippingMethod::find($data['shipping_method']);

        foreach($cart as $item) {
            if(!empty($shipping_method->name) && strtolower($shipping_method->name) == 'standard') {
                $item['weight_gram'] = $item['weight_gram_max'];
            }
            $product = new Product();
            $product->order_id = $this->id;
            $product->title = $item['title'];
            $product->shop_price = $item['shop_price'];
            $product->quantity = $item['quantity'];
            $product->product_id = $item['id'];
            $product->variant = !empty($item['variant_name']) ? $item['variant_name'] : null;
            $product->image = !empty($item['image']) ? $item['image'] : null;
            $product->shipping_weight = !empty($item['shipping_weight']) ? $item['shipping_weight'] : null;
            $product->shipping_dimension = !empty($item['shipping_dimension']) ? $item['shipping_dimension'] : null;
            $product->weight_gram = !empty($item['weight_gram']) ? $item['weight_gram'] : null;
            $product->save();
            //$product->url = $item['url'];
            //$product->color = !empty($item['color']) ? $item['color'] : '';
            //$product->style = !empty($item['style']) ? $item['style'] : '';
            //$product->conf = !empty($item['conf']) ? $item['conf'] : '';
            //$product->size = !empty($item['size']) ? $item['size'] : '';
            //$product->shipping_dimension = !empty($item['shipping_dimension']) ? $item['shipping_dimension'] : '';
            //$product->product_weight = !empty($item['product_weight']) ? $item['product_weight'] : '';
            //$product->product_dimension = !empty($item['product_dimension']) ? $item['product_dimension'] : '';
        }

        return true;
    }

    /**
     * @return int|number
     */
    public function getTotalWeight()
    {
        $weight = array_sum(array_map(function($item) {
            return !empty($item['shipping_weight']) ? explode(" ", $item['shipping_weight'])[0] : 0;
        }, $this->products->toArray()));

        return $weight ? $weight : 1;
    }

    /**
     * Total price only by products
     *
     * @return mixed
     */
    public function getTotalPrice()
    {
        return $this->products->sum(function($item) {
            return $item->getTotalPrice();
        });
    }

    /**
     * Total price by products with discount and shipping * rate
     *
     * @return mixed
     */
    public function getFullTotalPrice()
    {
        return (($this->getTotalPrice()* $this->rate) - $this->discount); // + $this->shipping_total
    }

    /**
     * @return string
     */
    public function showStatus()
    {
        return trans('orders.status.'.$this->status);
    }

    public function getOrders($data = [])
    {
        switch (@$data['limit']) {
            case 50:
                $limit = 50;
                break;
            case 100:
                $limit = 100;
                break;
            default:
                $limit = 10;
                break;
        }

        $order_key = 'created_at';
        $order_value = 'desc';
        if(!empty(@$data['sort'])) {
            $order_parser = explode('_', @$data['sort']);
            $order_value = $order_parser[count($order_parser) - 1];
            unset($order_parser[count($order_parser) - 1]);
            $order_key = implode('_',$order_parser);
        }

        $orders = self::join('products', function ($join) {
                $join->on('products.order_id', '=', 'orders.id');
            })->with('user', 'products', 'shippingMethod', 'marketplace')
            ->ByStatus(@$data['status'])
            ->select('orders.*', DB::raw("sum(products.shop_price) as cost"))
            ->groupBy('orders.id')
            ->ByMarketplace(@$data['marketplace'])
            ->ByWord(@$data['q'])
            ->ByName(@$data['name'])
            ->orderBy($order_key, $order_value)
            ->paginate($limit);

        return $orders;
    }
}
