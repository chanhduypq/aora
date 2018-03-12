<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;

class ZincRequest extends Model
{
    protected $primaryKey = 'zinc_reference';
    public $incrementing = false;

    protected $fillable = ['zinc_reference', 'for_orders'];
}