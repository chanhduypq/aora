<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Country;
use App\blacklistplace;
use App\Blacklist;
use App\Marketplace;
use Illuminate\Http\Request;

class BlackListController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $blacklist = new Blacklist();
        $blacklists = $blacklist->getBlacklist($request->all());
        return view('admin.blacklists.list', [
            'blacklists' => $blacklists,
            'markets' => Marketplace::all(),
            'statuses' => trans('blacklist.status'),
            'types' => trans('blacklist.type'),
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $blacklist = Blacklist::findOrFail($id);
        if ($request->isMethod('post')) {
            $blacklist->name = $request->input('name');
            $blacklist->node = $request->input('node');
            $blacklist->type = $request->input('type', 0);
            $blacklist->market_id = $request->input('market_id');
            $blacklist->country_id = $request->input('country_id');
            $blacklist->status = $request->input('status', 0);
            $blacklist->save();
            return redirect()->route('admin.blacklist');
        }

        return view('admin.blacklists.form', [
            'countries' => Country::all(),
            'markets' => Marketplace::all(),
            'types' => trans('blacklist.type'),
            'blacklist' => $blacklist
        ]);
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $blacklist = new Blacklist();
        if ($request->isMethod('post')) {
            $blacklist->name = $request->input('name');
            $blacklist->node = $request->input('node');
            $blacklist->type = $request->input('type', 0);
            $blacklist->market_id = $request->input('market_id');
            $blacklist->country_id = $request->input('country_id');
            $blacklist->status = $request->input('status', 0);
            $blacklist->save();
            return redirect()->route('admin.blacklist');
        }
        return view('admin.blacklists.form', [
            'countries' => Country::all(),
            'markets' => Marketplace::all(),
            'types' => trans('blacklist.type'),
            'blacklist' => $blacklist
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete($id)
    {
        $blacklist = Blacklist::findorfail($id);
        $blacklist->delete();
        echo json_encode(array(
            'success' => true
        ));
        exit();
    }
}
