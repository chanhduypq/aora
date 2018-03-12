<?php

namespace App\Http\Controllers;

class TestController extends Controller
{
    const TOKEN =  'demo token';

    /**
     * connect singpost
     */
    public function connect()
    {
        $connect = $this->singpost_curl('https://api.singpost.com');
        exit(json_encode($connect));
    }

    /**
     * singpost create package
     */
    public function createPackage()
    {
        $package = array(
            "box_no"=> "M00001",
            "status"=> "SO",
            "weight"=> 5.23,
            "width"=> 19,
            "length"=> 32,
            "height"=> 41
        );
        $create_package = $this->singpost_curl('https://api.singpost.com/v1/packages', 'POST', $package);
        exit(json_encode($create_package));
    }

    /**
     * singpost edit package
     */
    public function editPackage($package_id)
    {
        $package = array(
            "box_no"=> "M00001",
            "status"=> "SO",
            "weight"=> 5.23,
            "width"=> 19,
            "length"=> 32,
            "height"=> 41
        );
        $edit_package = $this->singpost_curl('https://api.singpost.com/v1/packages/'.$package_id, 'PATCH', $package);
        exit(json_encode($edit_package));
    }

    /**
     * singpost track package
     */
    public function trackPackage($package_id)
    {
        $track = array(
            "status"=> "SO",
            "created_at"=> "2017-01-01T00:01:45Z",
            "data"=> array(
                "airway_bill_no"=> "A00001",
                "shipped_at"=> "2017-01-01T00:01:45Z",
                "country_code"=> "SG"
            )
        );
        $track_package = $this->singpost_curl('https://api.singpost.com/v1/packages/'.$package_id.'/tracking', 'POST', $track);
        exit(json_encode($track_package));
    }

    /**
     * singpost create item
     */
    public function createItem($package_id)
    {
        $item = array(
            "id"=> "I00001",
            "category"=> "Women's Clothes",
            "description"=> "t-shirt",
            "brand"=> "Nike",
            "size"=> "M",
            "colour"=> "Green",
            "currency"=> "USD",
            "value"=> 7.45,
            "rate"=> 6.33,
            "qty"=> 1
        );
        $create_item = $this->singpost_curl('https://api.singpost.com/v1/packages/'.$package_id.'/items', 'POST',$item);
        exit(json_encode($create_item));
    }

    /**
     * singpost edit item
     */
    public function editItem($item_id)
    {
        $item = array(
            "category"=> "Women's Clothes",
            "description"=> "t-shirt",
            "brand"=> "Nike",
            "size"=> "M",
            "colour"=> "Green",
            "currency"=> "USD",
            "value"=> 7.45,
            "rate"=> 6.33,
            "qty"=> 1
        );
        $package_id= '';
        $edit_item = $this->singpost_curl('https://api.singpost.com/v1/packages/'.$package_id.'/items/'.$item_id, 'PATCH',$item);
        exit(json_encode($edit_item));
    }

    /**
     * singpost delete item
     */
    public function deleteItem($item_id)
    {
        $package_id= '';
        $delete_item = $this->singpost_curl('https://api.singpost.com/v1/packages/'.$package_id.'/items/'.$item_id, 'DELETE');
        exit(json_encode($delete_item));
    }

    /**
     * singpost create order
     */
    public function createOrder()
    {
        $order = array(
            "id"=> "O00001",
            "country_code"=> "USA",
            "vpnumber"=> "AA00000001",
            "payment_date"=> "2017-01-01T15:23:02Z",
            "invoice_no"=> "000000000001",
            "currency"=> "USD",
            "total_product_value"=> 100.00,
            "total_shipping_value"=> 24.95,
            "total_item_count"=> 3,
            "customer_email"=> "customer@example.com",
            "contact_no"=> "+8455555555",
            "recipient_name"=> "Jane Doe",
            "delivery_addr1"=> "10 Eunos Road 8",
            "delivery_addr2"=> "Singapore Post Centre",
            "postal_code"=> "408600",
            "city"=> "Singapore",
            "length"=> 24,
            "width"=> 12,
            "height"=> 10,
            "packages" => array(
                array(
                    "id"=> "P00001",
                    "merchant"=> "Amazon",
                    "tracking_no"=> "TR00000000000001X",
                    "insurance_value"=> 10.00,
                    "eta"=> "2017-01-04T00:00:00Z",
                    "vpnumber"=> "AA00000001",
                    "items"=> array(
                        array(
                            "id"=> "I00001",
                            "category"=> "Women's Clothes",
                            "description"=> "t-shirt",
                            "brand"=> "Nike",
                            "size"=> "M",
                            "colour"=> "Green",
                            "currency"=> "USD",
                            "value"=> 7.45,
                            "rate"=> 6.33,
                            "qty"=> 1,
                            "url"=> "www.amazon.com/xx/xx"
                        ),
                        array(
                            "id"=> "I00002",
                            "category"=> "Women's Clothes",
                            "description"=> "shorts",
                            "brand"=> "Nike",
                            "size"=> "L",
                            "colour"=> "Black",
                            "currency"=> "USD",
                            "value"=> 27.45,
                            "rate"=> 6.33,
                            "qty"=> 1,
                            "url"=> "www.amazon.com/xx/xx"
                        ),
                    ),
                ),
                array(
                    "id"=> "P00002",
                    "merchant"=> "Amazon",
                    "tracking_no"=> "TR00000000000002X",
                    "insurance_value"=> 8.00,
                    "eta"=> "2017-01-05T00:00:00Z",
                    "vpnumber"=> "AA00000001",
                    "items"=> array(
                        array(
                            "id"=> "I00003",
                            "category"=> "Women's Clothes",
                            "description"=> "Running Shoes",
                            "brand"=> "New Balance",
                            "size"=> "32",
                            "colour"=> "white",
                            "currency"=> "USD",
                            "value"=> 47.45,
                            "rate"=> 6.33,
                            "qty"=> 1,
                            "url"=> "www.amazon.com/xx/xx"
                        )
                    ),
                ),
            ),
        );

        $create_order = $this->singpost_curl('https://api.singpost.com/v1/orders', 'POST', $order);
        exit(json_encode($create_order));
    }

    private function singpost_curl($url, $method = 'GET', $params = array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);

        $params_str = http_build_query($params);

        $header = array(
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
            'Authorization: token '. self::TOKEN
        );
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        if(strtoupper($method) == 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS,$params_str);
        } elseif(strtoupper($method) == 'PATCH') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        } elseif(strtoupper($method) == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec ($ch);

        curl_close ($ch);
        return $result;
    }

}