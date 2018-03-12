<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Marketplace extends Model
{
    protected $table = 'marketplaces';
    protected $fillable = ['*'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function scopeByWord($query, $value)
    {
        if($value) {
            return $query->where('name', 'like', "%$value%");
        }
    }
    public function scopeByCountry($query, $value)
    {
        if($value) {
            return $query->where('country_id', '=', $value);
        }
    }

    public function getMarketPlaces($data = [])
    {
        $limit = 10;
        if(!empty(@$data['limit'])) {
            $limit = @$data['limit'];
        }

        $order_key = 'created_at';
        $order_value = 'desc';
        if(!empty(@$data['sort'])) {
            $order_parser = explode('_', @$data['sort']);
            $order_value = $order_parser[count($order_parser) - 1];
            unset($order_parser[count($order_parser) - 1]);
            $order_key = implode('_',$order_parser);
        }

        $marketPlaces = self::ByWord(@$data['name'])
            ->ByCountry(@$data['country_id'])
            ->orderBy($order_key, $order_value)
            ->paginate($limit);

        return $marketPlaces;
    }
}
