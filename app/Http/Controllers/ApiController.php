<?php

namespace App\Http\Controllers;

use App\Classes\DHL;
use App\Classes\Parser;
use App\Traits\CartTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiController extends Controller
{
    use CartTrait;

    public $jsonOptions = JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE;

    /**
     * @param $asin
     * @return mixed
     */
    public function getProduct($id)
    {
        $parser = new Parser();
        $product = $parser->parseAmazonFromAPI($id);

        if(!$product) {
            return response()->json(['status' => 'error', 'msg' => 'Product not found.']);
        }

        $data = [
            'id' => $product->id,
            'title' => $product->title,
            'image' => $product->image,
            'price' => $product->price,
            'dimensions' => $product->dimensions,
            'dimensionsAsString' => $product->dimensionsAsString,
            'weight' => $product->weight,
            'weightGram' => $product->weightGram,
            'variations' => isset($product->variations) ? $product->variations : [],
        ];

        return response()->json(['status' => 'ok', 'data' => $data], Response::HTTP_OK, [], $this->jsonOptions);
    }

    public function array_keys_exists(array $keys, array $arr)
    {
        return !array_diff_key(array_flip($keys), $arr);
    }

    /**
     * @param $asin
     * @return mixed
     */
    public function getProductVariant(Request $request)
    {
        if(!$request->ajax() || !$request->has('options')) {
            return response()->json(['status' => 'error', 'msg' => 'Empty request!']);
        }

        $variations = session()->get('variations');
        $options = $request->get('options');
        $keys = array_keys($options);
        $values = array_values($options);
        $product_id = 0;

        $variant = array_first($variations, function($item, $id) use($keys, $values, &$product_id) {
            $r = $this->array_keys_exists($keys, $item);
            $d = array_intersect(array_values($item), $values);

            if($r && count($d) == count($values)) {
                $product_id = $id;
                return true;
            }

            return false;
        });

        if(!$product_id) {
            return response()->json(['status' => 'error', 'msg' => 'Product not found this variant!']);
        }

        $parser = new Parser();
        $product = $parser->getProductFromZinc($product_id);

        if(!$product) {
            return response()->json(['status' => 'error', 'msg' => 'Empty response from api!']);
        }

        $data = [
            'id' => $product->id,
            'title' => $product->title,
            'image' => $product->image,
            'price' => $product->price,
            'dimensions' => $product->dimensionsAsString,
            'weight' => $product->weight,
            'weight_gram' => $product->weightGram,
        ];

        return response()->json(['status' => 'ok', 'data' => $data], Response::HTTP_OK, [], $this->jsonOptions);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getDhl(Request $request)
    {
        if(!$request->ajax() || !$request->has('postal_code')) {
            return response()->json(['status' => 'error']);
        }

        $dhl = new DHL();
        $dhlShipp = $dhl->getDhlShipping($request->get('postal_code'), $this->getCart());

        if (!empty($dhlShipp)) {
            $shipping = array_column($dhlShipp, 'ShippingCharge');
            $lowest_shipping = !empty($shipping) ? min($shipping) : 0;

            $data['lowest_shipping'] = $lowest_shipping;
            $data['dhl'] = view('partials.dhl_shipping', [
                'dhl' => $dhlShipp,
                'selected_shipping' => $lowest_shipping
            ])->render();

            return response()->json(['status' => 'ok', 'data' => $data]);
        } else {
            return response()->json(['status' => 'error']);
        }
    }
}
