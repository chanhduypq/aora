<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class EmailKey extends Model
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

    public function showStatus()
    {
        return trans('email_keys.status.'.$this->status);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function create(array $data)
    {
        $this->keyword = $data['code'];
        $this->description = $data['subject'];
        $this->status = $data['status'];
        $this->save();
        return true;
    }

}
