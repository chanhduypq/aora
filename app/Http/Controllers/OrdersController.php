<?php

namespace App\Http\Controllers;

use App\Order;
use App\ZincRequest;
use Illuminate\Support\Facades\Auth;
use App\Notifications\OrderProcess;
use App\Classes\Zinc\Api as Zinc;
use Carbon\Carbon;
use Mail;

class OrdersController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function orders()
    {
        $user = Auth::user();

        return view('orders.orders', [
            'orders' => $user->getOrders(),
        ]);
    }

    public function track($order_id)
    {
        $order = Order::findOrFail($order_id);

        return view('orders.track', [
            'order' => $order,
        ]);
    }

    public function autoCancelScheduler()
    {
        $orders = Order::where('status', Order::ORDER_UNPAID)->get();

        foreach ($orders as $order)
        {
            $diff_time = (time() - strtotime($order->created_at));
            if($diff_time > 3600) {
                $order->status = Order::ORDER_CANCEL;
                $order->save();
                $order->notify(new OrderProcess($order));
            }
        }
    }

    public function autoZincOrder()
    {
        $orders = Order::where('status', Order::ORDER_NEW)->whereNull('zinc_reference')->limit(1)->get();

        $selected_orders = [];
        $order_total = 0;
        foreach ($orders as $order) {
            if ($order_total >= 25)
                break;

            $selected_orders[] = $order;
            
            $order_total += round($order->getTotalPrice(), 2);
        }

        if (empty($selected_orders))
            return;

        $order_products = [];

        foreach ($selected_orders as $order) {
            foreach ($order->products as $product)
                $order_products[] = [
                    'id' => $product->product_id,
                    'quantity' => $product->quantity,
                ];
        }

        $order_data = [
            'products' => $order_products,            
            'shipping_address' => [
                'first_name' => 'SG23951634',
                'last_name' => 'Vivienne Ong',
                'address_line1' => '14601 N Bybee Lake Court',
                'address_line2' => 'Suite SG23951634',
                'zip_code' => '97203',
                'city' => 'Portland',
                'state' => 'OR',
                'country' => 'US',
                'phone_number' => '109824853'
            ]
        ];

        /***
        'shipping_address' => [
                'first_name' => 'SG23951634',
                'last_name' => 'Vivienne Ong',
                'address_line1' => 'HDB Kembangan Estate, 112',
                'address_line2' => 'Lengkong Tiga',
                'zip_code' => '410112',
                'city' => 'Singapore',
                'state' => 'Singapore',
                'country' => 'SG',
                'phone_number' => '109824853'
            ]
        ****/

        $zincAPI = new Zinc(config('services.zinc.secret_key'));
        $request_id = $zincAPI->setOrder($order_data, $order_total * 100); //

        if ($request_id) {
            foreach ($selected_orders as $order) {
                $order->status = Order::ORDER_PROCESSING;
                $order->zinc_reference = $request_id;

                $order->save();
            }

            ZincRequest::create([
                'zinc_reference' => $request_id,
                'for_orders' => serialize(array_column($selected_orders, 'id')),
            ]);
        }
    }

    public function checkZincStatus()
    {
        $requests = ZincRequest::whereNull('processed_at')->limit(10)->get();

        $zincAPI = new Zinc(config('services.zinc.secret_key'));

        foreach ($requests as $request) {
            $response = $zincAPI->getOrderRequestStatus($request->zinc_reference);
            
            if (isset($response->code) && $response->code == 'request_processing')
                continue;
            elseif ($response->_type == 'error') {
                $orders = Order::where('zinc_reference', $request->zinc_reference)->get();
                foreach ($orders as $order){
                    $order->status = Order::ORDER_CANCEL;
                    //$order->zinc_reference = null;

                    $order->save();
                }
            } elseif ($response->_type == 'order_response') {
                $orders = Order::where('zinc_reference', $request->zinc_reference)->get();
                foreach ($orders as $order){
                    $order->status = Order::ORDER_LEAVING_USA;
                    $order->save();

                    $products = [];
                    foreach ($order->products as $product)
                        $products[] = $product->title;

                    // send email
                    $order->notify(new OrderProcess($order));                    
                }
            }

            $request->processed_at = Carbon::now();
            $request->data = serialize($response);
            $request->save();
        }
    }
}
