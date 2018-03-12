<?php

/**
 * Created by PhpStorm.
 * User: Nitin
 * Date: 03-11-2017
 * Time: 18:09
 */

namespace App\Classes\Zinc;

class Product
{
    private $data;
    public $id, $title, $brand, $description, $stars, $price, $retailer, $image, $ean, $variations, $specifics,
        $variationsGroup, $dimensionsAsString, $weightGram, $weight;

    public function __construct($data)
    {
        $this->data = $data;

        $this->currentVariant = $this->currentVariant();
        $this->variations = $this->variations();
        $this->variationsGroup = $this->variationsGroup();
        $this->weight = $this->weight();
        $this->weightGram = $this->weight('gram');
        $this->dimensionsAsString = $this->dimensionsAsString();
        $this->dimensions = $this->dimensions();

        if (!empty($data->variant_specifics))
            $this->specifics = $data->variant_specifics;

        if (!empty($data->product_id))
            $this->id = $data->product_id;

        if (!empty($data->product_id))
            $this->id = $data->product_id;

        if (!empty($data->product_id))
            $this->id = $data->product_id;
        
        if (!empty($data->title))
            $this->title = $data->title;
            
        if (!empty($data->brand))
            $this->brand = $data->brand;
            
        if (!empty($data->product_description))
            $this->description = $data->product_description;
            
        if (!empty($data->stars))
            $this->stars = $data->stars;
            
        if (!empty($data->price))
            $this->price = round($data->price / 100, 2);
            
        if (!empty($data->retailer))
            $this->retailer = $data->retailer;
            
        if (!empty($data->main_image))
            $this->image = $data->main_image;

        if (!empty($data->epids_map->EAN))
            $this->ean = $data->epids_map->EAN;
    }

    public function getVariationsTitle()
    {
        return !empty($this->variations) ? implode(' + ', array_keys(current($this->variations))) : null;
    }

    public function currentVariant()
    {
        $variations = array();

        if (!empty($this->data->variant_specifics)) {
            foreach ($this->data->variant_specifics as $attribute) {
                $variations[$attribute->dimension] = $attribute->value;
            }
        }

        return $variations;
    }

    public function variationsGroup()
    {
        $variations = array();

        if (!empty($this->data->all_variants)) {
            foreach ($this->data->all_variants as $variant) {
                foreach ($variant->variant_specifics as $attribute) {
                    if(!isset($variations[$attribute->dimension]) || !in_array($attribute->value, $variations[$attribute->dimension])) {
                        $variations[$attribute->dimension][] = $attribute->value;
                    }
                }
            }
        }

        foreach($variations as $k => &$v) {
            sort($v);
        }

        return $variations;
    }

    public function variations()
    {
        $variations = array();

        if (!empty($this->data->all_variants)) {
            foreach ($this->data->all_variants as $variant)
                foreach ($variant->variant_specifics as $attribute)
                    $variations[$variant->product_id][$attribute->dimension] = $attribute->value;
        }

        return $variations;
    }

    public function dimensionsAsString($unit = 'inches')
    {
        $d = $this->dimensions($unit);

        if(!empty($d)) {
            return implode(' x ', $d);
        }

        return;
    }

    public function dimensions($unit = 'inches')
    {
        $dimensions = array();

        if (!empty($this->data->package_dimensions->size)) {
            $dimensions['width'] = $this->data->package_dimensions->size->width->amount;
            $dimensions['depth'] = $this->data->package_dimensions->size->depth->amount;
            $dimensions['length'] = $this->data->package_dimensions->size->length->amount;
        }

        return $dimensions;
    }

    public function weight($unit = 'ounces')
    {
        if (empty($this->data->package_dimensions->weight)) {
            return 0;
        }

        $weight = $this->data->package_dimensions->weight->amount;
        $_unit = $this->data->package_dimensions->weight->unit;

        if ($unit == 'ounces' && $_unit == 'pound') {
            $weight = round($weight * 0.0625, 2);
        } elseif ($unit == 'pound') {
            $weight = round($weight * 0.0625, 2);
        } elseif ($unit == 'gram' && $_unit == 'ounces') {
            $weight = round($weight * config('settings.units.ounce_eq_gram'), 2);
        } elseif ($unit == 'gram' && $_unit == 'pounds') {
            $weight = round($weight * config('settings.units.pound_eq_gram'), 2);
        }

        return $weight;
    }
    
}