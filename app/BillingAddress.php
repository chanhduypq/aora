<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;

class BillingAddress extends Model
{
    protected $table = 'billing_addresses';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * @param $post
     * @return bool|mixed|string
     */
    public function create($post)
    {
        if(empty($post['billing_id'])) {
            return false;
        } elseif($post['billing_id'] == 'shipping') {
            return 'shipping';
        } elseif($post['billing_id'] == 'new') {

            $data = array_filter($post['billing']);

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
        } elseif((int) $post['billing_id'] > 0) {
            return $post['billing_id'];
        } else {
            return false;
        }
    }
}
