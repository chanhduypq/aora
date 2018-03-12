<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{

    CONST EMAIL_INACTIVE = 0;
    CONST EMAIL_ACTIVE = 1;

    public $dates = ['updated_at','created_at'];
    public $fillable = ['*'];

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::EMAIL_ACTIVE);
    }
    public function scopeInActive($query)
    {
        return $query->where('status', self::EMAIL_INACTIVE);
    }
    public function scopeByStatus($query, $value)
    {
        if(!is_null($value)) {
            return $query->where('status', $value);
        }
    }

    public function scopeByWord($query, $value)
    {
        if($value) {
            return $query->where(function($q) use ($value) {
                $q->where('subject', 'like', "%$value%")
                    ->orWhere('content', 'like', "%$value%")
                    ->orWhere('code', 'like', "%$value%");
            });
        }
    }

    public function showStatus()
    {
        return trans('emails.status.'.$this->status);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data)
    {
        $this->code = $data['code'];
        $this->subject = $data['subject'];
        $this->content = $data['content'];
        $this->attachment = $data['attachment'];
        $this->status = $data['status'];
        $this->save();
        return true;
    }

    public function getEmails($data = [])
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

        $orders = self::ByStatus(@$data['status'])->ByWord(@$data['code'])
            ->orderBy($order_key, $order_value)
            ->paginate($limit);

        return $orders;
    }

    public function get_codeView($type, $key) {
        $data = array(
            'code' => '',
            'view' => ''
        );
        if($type == 'user') {
            switch ($key) {
                case 'forgot_password':
                    $data['code'] = 'forgot_password';
                    $data['view'] = 'mail.users.forgotpassword';
                    break;
                case 'register':
                    $data['code'] = 'user_registration';
                    $data['view'] = 'mail.users.registration';
                    break;
            }
        }
        if($type == 'order') {
            switch ($key) {
                case Order::ORDER_NEW:
                    $data['code'] = 'order_created';
                    $data['view'] = 'mail.orders.created';
                    break;
                case Order::ORDER_LEAVING_USA:
                    $data['code'] = 'order_leaving_usa';
                    $data['view'] = 'mail.orders.leaving_usa';
                    break;
                case Order::ORDER_ARRIVED_SG:
                    $data['code'] = 'order_arrived_sg';
                    $data['view'] = 'mail.orders.arrived_sg';
                    break;
                case Order::ORDER_SHIPPING:
                    $data['code'] = 'order_shipping';
                    $data['view'] = 'mail.orders.shipping';
                    break;
                case Order::ORDER_COMPLETE:
//                $data['code'] = 'order_complete';
//                $data['view'] = 'mail.orders.complete';
                    break;
                case Order::ORDER_CANCEL:
//                $data['code'] = 'order_cancel';
//                $data['view'] = 'mail.orders.cancel';
                    break;
            }
        }
        return $data;
    }

}
