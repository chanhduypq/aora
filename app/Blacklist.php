<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    const TYPE_PRODUCT = 0;
    const TYPE_CATEGORY = 1;

    CONST INACTIVE = 0;
    CONST ACTIVE = 1;

    protected $table = 'blacklist';
    protected $fillable = ['*'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function market()
    {
        return $this->belongsTo('App\Marketplace');
    }

    public function scopeByWord($query, $value)
    {
        if($value) {
            return $query->where('name', 'like', "%$value%");
        }
    }
    public function scopeByNode($query, $value)
    {
        if($value) {
            return $query->where('node', 'like', "%$value%");
        }
    }
    public function scopeByType($query, $value)
    {
        if(!is_null($value)) {
            return $query->where('type', '=', "$value");
        }
    }
    public function scopeByStatus($query, $value)
    {
        if(!is_null($value)) {
            return $query->where('status', '=', "$value");
        }
    }
    public function scopeByMarket($query, $value)
    {
        if(!is_null($value)) {
            return $query->where('market_id', $value);
        }
    }

    public function getActiveBlacklist()
    {
        $blacklists = self::ByStatus(self::ACTIVE)->get();
        $nodes = array();
        foreach ($blacklists as $blacklist) {
            $nodes[] = $blacklist->node;
        }
        return $nodes;
    }

    public function getBlacklist($data = [])
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

        $blacklist = self::ByWord(@$data['name'])
            ->ByMarket(@$data['market_id'])
            ->ByType(@$data['type'])
            ->ByNode(@$data['node'])
            ->ByStatus(@$data['status'])
            ->orderBy($order_key, $order_value)
            ->paginate($limit);

        return $blacklist;
    }
}
