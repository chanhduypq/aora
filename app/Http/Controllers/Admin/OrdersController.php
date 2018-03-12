<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Marketplace;
use App\Order;
use App\EmailSent;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Notifications\OrderProcess;

class OrdersController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $order = new Order();
        $orders = $order->getOrders($request->all());
        $marketplaces = Marketplace::all();

        return view('admin.orders.list', [
            'orders' => $orders,
            'statuses' => trans('orders.status'),
            'marketplaces' => $marketplaces,
        ]);
    }

    /**
     * @param Int id,Int status
     * json
     */
    public function updateStatus(Request $request) : JsonResponse
    {
        if($request->has('id') && $request->has('status')) {
            $order = Order::find($request->get('id'));
            if($order->status != $request->get('status')) {
                $order->status = $request->get('status');
                $order->save();
                $order->notify(new OrderProcess($order));
                $email_sent = new EmailSent();
                $email_sent->save_SentMail('order', $order);
            }
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createRefund(Request $request) : JsonResponse
    {
        if(!$request->has('id')) {
            return response()->json(['status' => 'fail']);
        }

        $order = Order::find($request->get('id'));

        if(!$order) {
            return response()->json(['status' => 'fail']);
        }

        try {
            $stripe = new Stripe(env('STRIPE_API_SECRET'));
            $refund = $stripe->refunds()->create($request->get('stripe_transaction_id'));
        }
        catch (\Exception $e) {
            return response()->json(['status' => 'fail']);
        }

        if($refund['status'] == 'succeeded') {
            $order->status = Order::ORDER_REFUND;
            $order->save();
            return response()->json(['status' => 'success']);
        }
    }
}
