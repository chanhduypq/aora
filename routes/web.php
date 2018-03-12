<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['auth', 'admin'])->group(function() {

    Route::prefix('admin')->group(function() {

        Route::any('/', [
            'as' => 'admin.dashboard',
            'uses' => 'Admin\DashboardController@index'
        ]);

        Route::prefix('orders')->group(function() {
            Route::any('/', [
                'as' => 'admin.orders',
                'uses' => 'Admin\OrdersController@index'
            ]);
            Route::post('/updateStatus', [
                'as' => 'admin.orders.updateStatus',
                'uses' => 'Admin\OrdersController@updateStatus'
            ]);
            Route::post('/createRefund', [
                'as' => 'admin.orders.createRefund',
                'uses' => 'Admin\OrdersController@createRefund'
            ]);
        });
        Route::prefix('emails')->group(function() {
            Route::any('/', [
                'as' => 'admin.emails',
                'uses' => 'Admin\EmailsController@index'
            ]);
            Route::any('/sent', [
                'as' => 'admin.emails.sent',
                'uses' => 'Admin\EmailsController@sent'
            ]);
            Route::any('/sent/{id}', [
                'as' => 'admin.emails.sentView',
                'uses' => 'Admin\EmailsController@sentView'
            ]);
            Route::any('/create', [
                'as' => 'admin.emails.create',
                'uses' => 'Admin\EmailsController@create'
            ]);
            Route::any('/{id}/edit', [
                'as' => 'admin.emails.edit',
                'uses' => 'Admin\EmailsController@edit'
            ]);
        });
        Route::prefix('users')->group(function() {
            Route::get('/', [
                'as' => 'admin.users',
                'uses' => 'Admin\UsersController@index'
            ]);
            Route::get('/deleted', [
                'as' => 'admin.users.deleted',
                'uses' => 'Admin\UsersController@deleted'
            ]);
            Route::any('/create', [
                'as' => 'admin.users.create',
                'uses' => 'Admin\UsersController@create'
            ]);
            Route::any('/{id}/edit', [
                'as' => 'admin.users.edit',
                'uses' => 'Admin\UsersController@edit'
            ]);
            Route::any('/{id}', [
                'as' => 'admin.users.view',
                'uses' => 'Admin\UsersController@view'
            ]);
            Route::post('/{id}/delete', [
                'as' => 'admin.users.delete',
                'uses' => 'Admin\UsersController@delete'
            ]);
        });


        Route::prefix('marketplaces')->group(function() {
            Route::get('/', [
                'as' => 'admin.marketplaces',
                'uses' => 'Admin\MarketPlaceController@index'
            ]);
            Route::any('/create', [
                'as' => 'admin.marketplaces.create',
                'uses' => 'Admin\MarketPlaceController@create'
            ]);
            Route::any('/{id}/edit', [
                'as' => 'admin.marketplaces.edit',
                'uses' => 'Admin\MarketPlaceController@edit'
            ]);
            Route::post('/{id}/delete', [
                'as' => 'admin.marketplaces.delete',
                'uses' => 'Admin\MarketPlaceController@delete'
            ]);
        });
        Route::prefix('blacklist')->group(function() {
            Route::get('/', [
                'as' => 'admin.blacklist',
                'uses' => 'Admin\BlackListController@index'
            ]);
            Route::any('/create', [
                'as' => 'admin.blacklist.create',
                'uses' => 'Admin\BlackListController@create'
            ]);
            Route::any('/{id}/edit', [
                'as' => 'admin.blacklist.edit',
                'uses' => 'Admin\BlackListController@edit'
            ]);
            Route::post('/{id}/delete', [
                'as' => 'admin.blacklist.delete',
                'uses' => 'Admin\BlackListController@delete'
            ]);
        });


    });

});

Route::middleware(['auth'])->group(function() {

    Route::prefix('user')->group(function() {
        Route::post('/update/pass', [
            'as' => 'user.change.pass',
            'uses' => 'UserController@changePassword'
        ]);
        Route::post('/update/email', [
            'as' => 'user.change.email',
            'uses' => 'UserController@changeEmail'
        ]);
        Route::post('/delete/address', [
            'as' => 'user.delete.address',
            'uses' => 'UserController@deleteAddress'
        ]);
        Route::post('/update', [
            'as' => 'user.update',
            'uses' => 'UserController@update'
        ]);
    });

    Route::get('/setting', [
        'as' => 'user.setting',
        'uses' => 'UserController@setting'
    ]);

    Route::prefix('orders')->group(function() {
        Route::get('/', [
            'as' => 'orders',
            'uses' => 'OrdersController@orders'
        ]);
        Route::get('/track/{order}', [
            'as' => 'orders.track',
            'uses' => 'OrdersController@track'
        ]);
        Route::post('/create', [
            'as' => 'orders.create',
            'uses' => 'OrdersController@create'
        ]);
        Route::get('/repay/{order}', [
            'as' => 'orders.repay',
            'uses' => 'CheckoutController@repay'
        ]);
    }); 
    
    Route::prefix('/checkout')->group(function() {
        Route::any('/', [
            'as' => 'checkout',
            'uses' => 'CheckoutController@checkout'
        ]);
        Route::post('/process', [
            'as' => 'checkout.process',
            'uses' => 'CheckoutController@checkoutProcess'
        ]);
        Route::get('/confirm', [
            'as' => 'checkout.confirm',
            'uses' => 'CheckoutController@checkoutConfirm'
        ]);
        Route::get('/cancel', [
            'as' => 'checkout.cancel',
            'uses' => 'CheckoutController@checkoutCancel'
        ]);

        Route::post('/stripe/checkout', [
            'as' => 'stripe.checkout',
            'uses' => 'CheckoutController@stripeCheckout'
        ]);
    });

    Route::prefix('/payment')->group(function() {
        Route::post('/create', [
            'as' => 'payment.create',
            'uses' => 'CheckoutController@payment'
        ]);
        Route::get('/done', [
            'as' => 'payment.done',
            'uses' => 'CheckoutController@done'
        ]);
        Route::get('/cancel', [
            'as' => 'payment.cancel',
            'uses' => 'CheckoutController@cancel'
        ]);
    });
    
});

Route::middleware(['web'])->group(function() {

    Route::post('/feedback', [
        'as' => 'feedback',
        'uses' => 'ToolsController@feedback'
    ]);
    Route::get('/about', [
        'as' => 'pages.about',
        'uses' => 'PagesController@about'
    ]);
    Route::get('/contact', [
        'as' => 'pages.contact',
        'uses' => 'PagesController@contact'
    ]);
    Route::get('/', [
        'as' => 'pages.home',
        'uses' => 'PagesController@home'
    ]);

    Route::get('reset_rtxvxsaer', [
        'uses' => 'PagesController@reset'
    ]);
    
    Route::get('zincOrder', [
        'as' => 'admin.orders.zincOrder',
        'uses' => 'OrdersController@autoZincOrder'
    ]);

    Route::get('zincStatus', [
        'as' => 'admin.orders.zincStatus',
        'uses' => 'OrdersController@checkZincStatus'
    ]);

    Route::prefix('cart')->group(function() {
        Route::post('/delete', [
            'as' => 'cart.delete',
            'uses' => 'CartController@removeItemFromCart'
        ]);
        Route::post('/update', [
            'as' => 'cart.changeQ',
            'uses' => 'CartController@changeItem'
        ]);
        Route::get('/', [
            'as' => 'cart',
            'uses' => 'CartController@cart'
        ]);
        Route::post('/add', [
            'as' => 'cart.add',
            'uses' => 'CartController@addToCart'
        ]);
    });

    Route::post('/ajax/product/variant', [
        'as' => 'ajax.getVariant',
        'uses' => 'ApiController@getProductVariant'
    ]);

    Route::post('/ajax/dhl', [
        'as' => 'ajax.getDhl',
        'uses' => 'ApiController@getDhl'
    ]);

    Route::any('/result', [
        'as' => 'pages.result',
        'uses' => 'PagesController@getResult'
    ]);

    Route::get('/result/{id}', [
        'as' => 'pages.result.byId',
        'uses' => 'PagesController@result'
    ]);

    Route::get('/register/verify/{token}', [
        'as' => 'register.verify',
        'uses' => 'UserController@verify'
    ]);
});

Route::get('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
});

Auth::routes();


Route::get('/test/singpost/connect', [
    'as' => 'test.singpost.connect',
    'uses' => 'TestController@connect'
]);
Route::get('/test/singpost/order/create', [
    'as' => 'test.singpost.order.create',
    'uses' => 'TestController@createOrder'
]);
Route::get('/test/singpost/package/create', [
    'as' => 'test.singpost.package.create',
    'uses' => 'TestController@createPackage'
]);
Route::get('/test/singpost/package/{id}/edit', [
    'as' => 'test.singpost.package.edit',
    'uses' => 'TestController@editPackage'
]);
Route::get('/test/singpost/package/{id}/track', [
    'as' => 'test.singpost.package.track',
    'uses' => 'TestController@trackPackage'
]);
Route::get('/test/singpost/package/{id}/item/create', [
    'as' => 'test.singpost.item.create',
    'uses' => 'TestController@createItem'
]);
Route::get('/test/singpost/item/{id}/edit', [
    'as' => 'test.singpost.item.edit',
    'uses' => 'TestController@editItem'
]);
Route::get('/test/singpost/item/{id}/delete', [
    'as' => 'test.singpost.item.delete',
    'uses' => 'TestController@deleteItem'
]);
