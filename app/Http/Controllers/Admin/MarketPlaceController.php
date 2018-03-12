<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Country;
use App\Marketplace;
use Illuminate\Http\Request;

class MarketPlaceController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $marketplace = new Marketplace();
        $marketplaces = $marketplace->getMarketPlaces($request->all());
        return view('admin.marketplaces.list', [
            'marketplaces' => $marketplaces,
            'countries' => Country::all()
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $market = Marketplace::findOrFail($id);
        if ($request->isMethod('post')) {
            $market->name = $request->input('name');
            $market->country_id = $request->input('country_id');
            $market->save();
            return redirect()->route('admin.marketplaces');
        }

        return view('admin.marketplaces.form', [
            'countries' => Country::all(),
            'marketplace' => $market
        ]);
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $market = new Marketplace();
        if ($request->isMethod('post')) {
            $market->name = $request->input('name');
            $market->country_id = $request->input('country_id');
            $market->save();
            return redirect()->route('admin.marketplaces');
        }
        return view('admin.marketplaces.form', [
            'countries' => Country::all(),
            'marketplace' => $market
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete($id)
    {
        $market = Marketplace::findorfail($id);
        $market->delete();
        echo json_encode(array(
            'success' => true
        ));
        exit();
    }
}
