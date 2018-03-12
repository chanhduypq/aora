<?php

namespace App\Http\Controllers;

use App\Classes\Parser;
use Illuminate\Http\Request;
use App\Order;
use App\Product;
use App\ProductsRecommendation;
use App\Blacklist;
use Symfony\Component\HttpFoundation\Session\Session;

class PagesController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function contact()
    {
        return view('pages.contact');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function about()
    {
        return view('pages.about');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getResult(Request $request)
    {
        if(!$request->has('link')) {
            return redirect()->route('pages.home');
        }

        $url = $request->get('link');
        $parser = new Parser();

        if(!$id = $parser->getCode($url)) {
            return redirect()->route('pages.home');
        }

        return redirect()->route('pages.result.byId', ['id' => $id]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function result($id)
    {
        if(!$id) {
            return redirect()->route('pages.home');
        }

        $parser = new Parser();
        $product = $parser->parseAmazonFromAPI($id);

        if($product) {
            session()->forget('variations');
            if (isset($product->variations)) {
                session()->put('variations', $product->variations);
            }
        } else {
            return redirect()->route('pages.home');
        }

        $product->in_blacklist = false;
        if(!empty($product->nodes)) {
            $blacklist = new Blacklist();
            $blacklists = $blacklist->getActiveBlacklist();
            if(!empty($blacklists)) {
                foreach ($product->nodes as $node) {
                    if (in_array($node, $blacklists)) {
                        $product->in_blacklist = true;
                        break;
                    }
                }
            }
        }
        return view('pages.result', ['product' => $product]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function home()
    {
        $productsRecommendation = ProductsRecommendation::orderBy('order_by')->get();
        return view('pages.home', [ 'productsRecommendation'=> $productsRecommendation]);
    }

    public function reset()
    {
        Product::where('order_id', '>', 3)->delete();
        Order::where('id', '>', 3)->delete();

        die('done');
    }
}
