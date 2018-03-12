@component('mail::message')
    <?php
    $content = nl2br($email->content);
    $content = str_replace('::name::',$order->user->name, $content);

    $content = str_replace('::order_id::',$order->id, $content);
    $content = str_replace('::site_currency::',$siteCurrency, $content);
    $total_price = $order->getFullTotalPrice();
    $content = str_replace('::total_price::',number_format($total_price, 2), $content);
    $content = str_replace('::count_products::',$order->products->count(), $content);

    $content = str_replace('::shipping_address::',($order->shippingAddress)?$order->shippingAddress->address:'', $content);
    $content = str_replace('::billing_address::',($order->billingAddress)?$order->billingAddress->address:'', $content);

    if(!$order->billingAddress) {
        $content = str_replace('<strong>Billing Address:</strong>','', $content);
    }

    $content = str_replace('::shipping_method_name::',$order->shippingMethod->name, $content);
    $content = str_replace('::shipping_method_duration::',$order->shippingMethod->duration, $content);

    $url_tracking = '<a href="'.URL::to('orders/track/' . $order->id).'" style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;border-radius:3px;color:#fff;display:inline-block;text-decoration:none;background-color:#3097d1;border-top:10px solid #3097d1;border-right:18px solid #3097d1;border-bottom:10px solid #3097d1;border-left:18px solid #3097d1" target="_blank">Track Order</a>';
    $content = str_replace('::button_tracking::',$url_tracking, $content);

    $table_product_list_html = '<table><tr><th style="width:100px;">Image</th><th>Name</th><th style="width:60px;">Price</th><th>Qty</th><th>Shipping</th><th></th></tr>';
    foreach ($order->products as $product) {
        $table_product_list_html .= '<tr>';
        $table_product_list_html .= '<td><img src="'.$product->image .'"></td>';
        $table_product_list_html .= '<td>'.$product->title.'</td>';
        $table_product_list_html .= '<td>'.$siteCurrency.' '.number_format($product->shop_price * $order->rate,2).'</td>';
        $table_product_list_html .= '<td>'.$product->quantity.'</td>';
        $table_product_list_html .= '<td>'.$order->shippingMethod->name.'</td>';
        $table_product_list_html .= '<td>';
        $table_product_list_html .= '<table>';
        $table_product_list_html .= '<tr>';
        $table_product_list_html .= '<td>Product Cost</td>';
        $table_product_list_html .= '<td>'.$siteCurrency.' '.number_format($product->shop_price * $product->quantity * $order->rate,2).'</td>';
        $table_product_list_html .= '</tr>';
        $table_product_list_html .= '<tr>';
        $table_product_list_html .= '<td>Shipping Fee</td>';
        $weight = ceil($product->quantity * $product->weight_gram / 100);
        $shipping_fee = $weight * ($order->shippingMethod->weight_charge + $order->shippingMethod->fuel_surcharge) + $order->shippingMethod->base_charge;
        $table_product_list_html .= '<td>'.$siteCurrency.' '.number_format($shipping_fee,2).'</td>';
        $table_product_list_html .= '</tr>';
        $table_product_list_html .= '<tr>';
        $table_product_list_html .= '<td>AORA Discount</td>';
        $discount = ($product->shop_price * $product->quantity * (config('settings.discount') / 100)) * $order->rate;
        $table_product_list_html .= '<td>'.$siteCurrency.' '.number_format($discount, 2).'</td>';
        $table_product_list_html .= '</tr>';
        $table_product_list_html .= '</tr>';
        $table_product_list_html .= '<tr>';
        $table_product_list_html .= '<td><strong>Sub Total</strong></td>';
        $table_product_list_html .= '<td><strong>'.$siteCurrency.' '.number_format($product->shop_price * $product->quantity * $order->rate + $shipping_fee - $discount, 2).'</strong></td>';
        $table_product_list_html .= '</tr>';
        $table_product_list_html .= '</table>';
        $table_product_list_html .= '</td>';
        $table_product_list_html .= '</tr>';
    }
    $table_product_list_html .= '</table>';
    $content = str_replace('::table_product_list::',$table_product_list_html, $content);

    $content = str_replace('::site_currency::',$siteCurrency, $content);
    $content = str_replace('::sub_total::',number_format($order->getTotalPrice() * $order->rate, 2), $content);
    $content = str_replace('::site_currency::',$siteCurrency, $content);
    $content = str_replace('::shipping_fee::',number_format($order->shipping_total, 2), $content);
    $content = str_replace('::site_currency::',$siteCurrency, $content);
    $content = str_replace('::aora_discount::',number_format($order->discount, 2), $content);
    $content = str_replace('::site_currency::',$siteCurrency, $content);
    $content = str_replace('::total::',number_format($order->getFullTotalPrice(), 2), $content);

    $url_logo = '<img src="'.URL::to('images/aora-logo.png').'">';
    $content = str_replace('::logo::',$url_logo, $content);

    echo $content;
    ?>
@endcomponent