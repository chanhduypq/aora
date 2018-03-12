<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    CONST USER_INACTIVE = 0;
    CONST USER_ACTIVE = 1;
    CONST USER_DELETED = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'address', 'postal_code', 'is_admin', 'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function billings()
    {
        return $this->hasMany('App\BillingAddress');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shippings()
    {
        return $this->hasMany('App\ShippingAddress');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('App\Order');
    }

    public function scopeByStatus($query, $value)
    {
        if(!is_null($value)) {
            return $query->whereIn('status',$value);
        }
    }
    public function scopeIsAdmin($query, $value)
    {
        if(!is_null($value)) {
            return $query->where('is_admin',$value);
        }
    }
    public function scopeByName($query, $value)
    {
        if($value) {
            return $query->where('name', 'like', "%$value%");
        }
    }
    public function scopeByEmail($query, $value)
    {
        if($value) {
            return $query->where('email', 'like', "%$value%");
        }
    }

    public function get_avatar() {
        $avatar = $this->avatar;
        if(empty($avatar) || $avatar == 'users/default.png') {
            return '/images/img-user.png';
        }
        return '/uploads/users/'.$avatar;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function findOrder($data = [])
    {
        $order = $this->orders()
            ->ByStatus(@$data['status'])
            ->ByPaypalStatus(@$data['paypal_status'])
            ->ByPaypalToken(@$data['paypal_token'])
            ->ByPaypalId(@$data['paypal_id'])
            ->orderBy('created_at', 'desc')
            ->first();

        return $order;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOrders()
    {
        $orders = $this->orders()
            ->orderBy('created_at', 'desc')
            ->with('products')
            ->get();

        return $orders;
    }

    public function getCountUsers($data = []) {
        return self::ByStatus(@$data['status'])
            ->IsAdmin(@$data['is_admin'])
            ->count();
    }

    public function getUsers($data = [])
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

        $users = self::ByStatus(@$data['status'])
            ->IsAdmin(@$data['is_admin'])
            ->ByName(@$data['name'])
            ->ByEmail(@$data['email'])
            ->orderBy($order_key, $order_value)
            ->paginate($limit);

        return $users;
    }
}
