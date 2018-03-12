<?php

namespace App\Classes;

class Helpers
{
    /**
     * @param $value
     * @return \Illuminate\Config\Repository|mixed|void
     */
    public static function convertPoundsToGrams($value)
    {
        if($value) {
            return config('settings.units.pound_eq_gram') * $value;
        }

        return $value;
    }

    public function admin_table_sortable($key) {
        $icon = '<i data-sort="'.$key.'" class="fa fa-sort dl-js-sortable';
        if(!is_null(request('sort')) && strpos(request('sort'),$key) === 0) {
            $icon .= ' dl-current-sort';
        }
        $icon .='"></i>';
        echo $icon;
    }
}