<?php

/**
 * Created by PhpStorm.
 * User: Nitin
 * Date: 03-11-2017
 * Time: 18:09
 */

namespace App\Classes\Zinc;

use GuzzleHttp\Client as HttpClient;

class Api
{
    private $client_token;
    private $retailer_logins;

    const BASE_URL = "https://api.zinc.io/v1/";

    public function __construct($client_token, $retailer_logins = [])
    {
        // Will set the API key required to perform action

        $this->client_token = $client_token;
        $this->retailer_logins = $retailer_logins;
    }

    public function getProduct($product_id, $retailer = 'amazon')
    {
        $data = $this->doRequest('products/'.$product_id.'?retailer='.$retailer);

        return new Product($data);
    }

    public function setOrder($payload, $order_amount = false, $retailer = 'amazon')
    {
        if (empty($payload['products'])) {
            throw new \Exception("You must provide the products you need to order");
        } elseif (empty($payload['shipping_address'])) {
            throw new \Exception("You must provide the shipping/billing address");
        } elseif (empty($this->retailer_logins[$retailer])) {
            //throw new \Exception("You must specify the Login details for this retailer");
        }

        $products = [];
        foreach ($payload['products'] as $product) {
            $variants = [];
            if (!empty($product['variants'])) {
                foreach ($products['variants'] as $dimension => $value) {
                    if (empty($dimension) || empty($value)) {
                        continue;
                    }

                    $variants[] = [
                        'dimension' => $dimension,
                        'value' => $value
                    ];
                }
            }

            $products[] = [
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'variants' => $variants,                
                'seller_selection_criteria' => [
                    'condition_in' => ['New']
                ]
            ];
        }

        $data = [
            'retailer' => $retailer,
            'products' => $products,
            'shipping_method' => 'cheapest',
            'shipping_address' => $payload['shipping_address'],
            'addax' => true,
        ];

        if ((float)$order_amount)
            $data['max_price'] = (float)$order_amount;

        $request_id = $this->doRequest('orders', 'POST', $data);

        if ($request_id) {
            return $request_id->request_id;
        }

        return false;
    }

    public function getOrderRequestStatus($request_id)
    {
        $response = $this->doRequest('orders/'.$request_id);
        return $response;
    }

    private function doRequest($resource, $method = "GET", $input = "")
    {
        $attr = [
            'auth' => [$this->client_token, ''],
        ];

        if ($method == 'POST' && !empty($input)) {
            $attr[\GuzzleHttp\RequestOptions::JSON] = $input;
        }

        $client = new HttpClient();
        try {
            $res = $client->request($method, self::BASE_URL . $resource, $attr);
        } catch (\GuzzleHttp\Exception\ConnectException $exception) {
            return false;
        }

        if ($res->getStatusCode() != 200) {
            throw new \Exception("Invalid Response");
        }

        $data = $res->getBody();

        if ($data) {
            return json_decode($data);
        }

        return false;
    }
}