<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class ShippingAddress extends Model
{
    protected $table = 'shipping_addresses';
    protected $fillable = ['*'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @param $post
     * @return bool|mixed
     */
    public function create($post)
    {
        if(empty($post['shipping_id'])) {
            return false;
        } else if($post['shipping_id'] == 'new') {

            $data = array_filter($post['shipping']);

            if(empty($data) || count($data) < 4) {
                return false;
            }

            $this->user_id = Auth::user()->id;
            $this->full_name = $data['full_name'];
            $this->postal_code = $data['postal_code'];
            $this->phone = $data['phone'];
            $this->address = $data['address'];
            $this->save();

            return $this->id;
        } elseif((int) $post['shipping_id'] > 0) {
            return $post['shipping_id'];
        } else {
            return false;
        }
    }
}
