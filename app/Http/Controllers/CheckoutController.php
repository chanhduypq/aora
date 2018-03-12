<?php

namespace App\Http\Controllers;

use App\Classes\DHL;
use App\Notifications\OrderProcess;
use App\Order;
use App\Rate;
use App\EmailSent;
use App\ShippingAddress;
use App\Traits\CartTrait;
use Carbon\Carbon;
use Cartalyst\Stripe\Stripe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Auth;
use Illuminate\View\View;
use Paypal;
use PayPal\Exception\PayPalConfigurationException;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Exception\PayPalInvalidCredentialException;

class CheckoutController extends Controller
{
    use CartTrait;

    private $_apiContext;

    const PAYMENT_METHOD_STRIPE = 'stripe';

    const PAYMENT_METHOD_PAYPAL = 'paypal';

    /**
     * CheckoutController constructor.
     */
    public function __construct()
    {
        $this->_apiContext = PayPal::ApiContext(
            config('services.paypal.client_id'),
            config('services.paypal.secret_key')
        );

        $this->_apiContext->setConfig(array(
            'mode' => config('services.paypal.mode'),
            'service.EndPoint' => config('services.paypal.api_url'),
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => storage_path('logs/paypal.log'),
            'log.LogLevel' => 'FINE'
        ));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkout(Request $request)
    {
        $cart = $this->getCart();

        if(!$cart) {
            return redirect()->route('pages.home');
        }
        
        $backParams = array(
            'error' => false,
            'full_name' => '',
            'postal_code' => '',
            'phone' => '',
            'address' => ''
        );

        if(!$request->has('shipping_method')) {
            if (!Session::has('shipping_method')) {
                return redirect()->route('cart');
            }
            $shippingTotal = Session::get('shipping_total');
            $shippingMethod = Session::get('shipping_method');
            $backParams = Session::get('shipping_address');
            $backParams['error'] = true;
        } else {
            $shippingTotal = $request->get('shipping_total', false);
            $shippingMethod = $request->get('shipping_method', false);
            Session::put('shipping_total', $shippingTotal);
            Session::put('shipping_method', $shippingMethod);
        }

        $total = $this->getCartTotal();
        $discountTotal = $total * (config('settings.discount') / 100);
        $total -= $discountTotal;
        //$total += $shippingTotal;
        $user = Auth::user();

        $shippings = ShippingAddress::where('user_id', $user->id)->get();

        $shippings = collect($shippings);
        $shippings->merge($user->shippings);

        return view('checkout.checkout', [
            'discountTotal' => $discountTotal,
            'shippingTotal' => $shippingTotal,
            'shippingMethod' => $shippingMethod,
            'shippings' => $shippings,
            'billings' => $user->billings,
            'total' => $total,
            'backParams' => $backParams
        ]);
    }

    public function checkoutCancel()
    {
        return view('checkout.cancel');
    }

    public function checkoutConfirm()
    {
        return view('checkout.confirm');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|View
     */
    public function checkoutProcess(Request $request)
    {
        $data = $request->all();
        $cart = $this->getCart();
        
        $shipping_address = array(
            'full_name' => isset($data['shipping']['full_name']) ? $data['shipping']['full_name'] :'',
            'postal_code' => isset($data['shipping']['postal_code']) ? $data['shipping']['postal_code'] :'',
            'phone' => isset($data['shipping']['phone']) ? $data['shipping']['phone'] :'',
            'address' => isset($data['shipping']['address']) ? $data['shipping']['address'] :'',
        );
        Session::put('shipping_address', $shipping_address);

        if(empty($data) || empty($cart)) {
            return redirect()->back();
        }

        $order = new Order();

        if(!$order->create($data, $cart)) {
            return redirect()->back();
        }
        $this->removeCart();

        return $this->selectPaymentMethod($data['payment'], $order);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function stripeCheckout(Request $request)
    {
        $stripe = new Stripe(env('STRIPE_API_SECRET'));
        $token  = $request->get('stripeToken');
        $orderId  = $request->get('order_id');
        $paymentHash  = $request->get('payment_hash');

        /**
         * Find user order
         */
        $order = Order::where(['id' => $orderId, 'payment_hash' => $paymentHash])->first();

        if(!$order) {
            throw new \LogicException('Something went wrong');
        }

        $total = round($order->getFullTotalPrice(), 2);

        try {
            $charge = $stripe->charges()->create([
                'currency' => 'SGD',
                'amount'   => $total,
                "source" => $token,
            ]);
        }
        catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if($charge['status'] !== 'succeeded') {
            throw new \Exception('Something went wrong');
        }

        $order->status = Order::ORDER_NEW;
        $order->stripe_transaction_id = $charge['id'];
        $order->save();
        $order->notify(new OrderProcess($order));
        $email_sent = new EmailSent();
        $email_sent->save_SentMail('order', $order);

        return redirect()->route('checkout.confirm');
    }

    /**
     * @param Order $order
     * @return View
     */
    public function paymentStripe(Order $order)
    {
        return view('checkout.stripe.stripe_index', ['order' => $order, 'totalPrice' => $order->getFullTotalPrice()]);
    }

    /**
     * @param $order_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function repay($order_id)
    {
        $order = Order::findOrFail($order_id);

        if($order->user_id != Auth::user()->id
            || $order->status == \App\Order::ORDER_COMPLETE) {
            return redirect()->route('orders');
        }

        return $this->selectPaymentMethod($order->payment_type, $order);
    }

    /**
     * @param string $paymentMethod
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse|View
     */
    private function selectPaymentMethod($paymentMethod, Order $order)
    {
        switch ($paymentMethod) {
            case self::PAYMENT_METHOD_PAYPAL:
                return $this->paymentPaypal($order);
                break;
            case self::PAYMENT_METHOD_STRIPE:
                return $this->paymentStripe($order);
                break;
            default:
                return redirect()->route('orders');
        }
    }

    /**
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paymentPaypal(Order $order)
    {
        try {
            $payer = PayPal::Payer();
            $payer->setPaymentMethod('paypal');

            $amount = PayPal::Amount();
            $amount->setCurrency(config('services.paypal.currency'));

            if(config('services.paypal.mode') == 'live') {
                $rate = Rate::orderBy('id', 'DESC')->first();
                $amount->setTotal($order->getFullTotalPrice() * $rate->price);
            } else {
                $amount->setTotal(1);
            }

            $transaction = PayPal::Transaction();
            $transaction->setAmount($amount);
            $transaction->setDescription('Test payment');

            $redirectUrls = PayPal::RedirectUrls();
            $redirectUrls->setReturnUrl(route('payment.done'));
            $redirectUrls->setCancelUrl(route('payment.cancel'));

            $payment = PayPal::Payment();
            $payment->setIntent('sale');
            $payment->setPayer($payer);
            $payment->setRedirectUrls($redirectUrls);
            $payment->setTransactions(array($transaction));
            
            $response = $payment->create($this->_apiContext);
            $redirectUrl = $response->links[1]->href;
            $pos = strpos($redirectUrl, '&token=')+strlen('&token=');
            $token = substr($redirectUrl, $pos, 25);

            $order->paypal_token = $token;
            $order->paypal_status = $response->state;
            $order->paypal_id = $response->id;
            $order->save();
        }
        catch(PayPalInvalidCredentialException $e) {
            throw new $e;
        }
        catch(PayPalConfigurationException $e) {
            throw new $e;
        }
        catch(PayPalConnectionException $e) {
            throw new $e;
        }

        return redirect()->to($redirectUrl);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function done(Request $request)
    {
        $id = $request->get('paymentId');
        $token = $request->get('token');
        $payer_id = $request->get('PayerID');

        $user = Auth::user();
        $order = $user->findOrder([
            'status' => Order::ORDER_UNPAID,
            'paypal_token' => $token,
            'paypal_id' => $id,
        ]);

        if(!$order) {
            return redirect()->route('pages.home');
        }

        $payment = PayPal::getById($id, $this->_apiContext);
        $paymentExecution = PayPal::PaymentExecution();
        $paymentExecution->setPayerId($payer_id);
        $executePayment = $payment->execute($paymentExecution, $this->_apiContext);

        if($executePayment->state == 'approved') {
            $order->status = Order::ORDER_NEW;
        }

        $order->paypal_status = $executePayment->state;
        $order->save();
        if($executePayment->state == 'approved') {
            $order->notify(new OrderProcess($order));
            $email_sent = new EmailSent();
            $email_sent->save_SentMail('order', $order);
        }

        return redirect()->route('orders');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(Request $request)
    {
        if(!$request->has('token')) {
            return redirect()->route('pages.home');
        }

        $user = Auth::user();
        $order = $user->findOrder([
            'status' => Order::ORDER_UNPAID,
            'paypal_token' => $request->get('token'),
        ]);

        if($order) {
            $order->status = Order::ORDER_CANCEL;
            $order->paypal_status = 'canceled';
            $order->save();
        }

        return redirect()->route('checkout.cancel');
    }
}
