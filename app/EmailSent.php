<?php

namespace App;
use App\Email;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;

class EmailSent extends Model
{

    CONST EMAIL_UNREAD = 0;
    CONST EMAIL_READ = 1;

    public $dates = ['updated_at','created_at'];
    public $fillable = ['*'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeByStatus($query, $value)
    {
        if(!is_null($value)) {
            return $query->where('status', $value);
        }
    }
    public function scopeByName($query, $value)
    {
        if(!is_null($value)) {
            return $query->whereHas('user', function($query) use($value) {
                $query->where('name', 'like', "%$value%");
            });
        }
    }
    public function showStatus()
    {
        if($this->status == self::EMAIL_READ)
            return 'Read';
        return 'Unread';
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

        $orders = self::ByName(@$data['name'])->orderBy($order_key, $order_value)
            ->paginate($limit);

        return $orders;
    }

    public function check_InvalidMail() {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.sendgrid.com/v3/suppression/invalid_emails",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json', 'Authorization: Bearer '.env('SENDGRID_API_KEY')
            ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            exit($response);
        }
    }

    public function save_SentMail($type, $obj, $key='') {
        if($type == 'order') {
            $email = new Email();
            $code_view = $email->get_codeView('order', $obj->status);
            $code = $code_view['code'];
            $view = $code_view['view'];

            $email = Email::where('code',$code)->first();

            $this->code = $code;
            $this->user_id = $obj->user->id;
            $this->to_email = $obj->user->email;
            $this->subject = $email->subject;
            $this->content = View::make($view, ['email' => $email,'order' => $obj])->render();
            $this->save();
        }
        if($type == 'user') {
            $email = new Email();
            $code_view = $email->get_codeView('user', $obj);
            $code = $code_view['code'];
            $view = $code_view['view'];
            $email = Email::where('code',$code)->first();
            if($obj == 'forgot_password') {
                $this->code = $code;
                $this->user_id = 0;
                $this->to_email = $key;
                $this->subject = $email->subject;
                $token = Session::get('token');
                $this->content = View::make($view, ['email' => $email, 'token' => $token])->render();
                $this->save();
            }
            if($obj == 'register') {
                $this->code = $code;
                $this->user_id = $key->id;
                $this->to_email = $key->email;
                $this->subject = $email->subject;
                $this->content = View::make($view, ['email' => $email, 'user' => $key])->render();
                $this->save();
            }
        }

    }
}
