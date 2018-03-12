<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUser;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function setting()
    {
        $user = Auth::user();
        $deliveries = $user->shippings;

        return view('user.setting', [
            'user' => $user,
            'deliveries' => $deliveries,
        ]);
    }

    /**
     * @param UpdateUser $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateUser $request)
    {
        $user = Auth::user();
        $user->update($request->all());

        return response()->json();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteAddress(Request $request)
    {
        if($request->ajax() && $request->has('id'))
        {
            $user = Auth::user();
            $user->shippings()->find($request->get('id'))->delete();
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function changeEmail(Request $request)
    {
        $user = Auth::user();

        if($user->email != $request->get('email') && Hash::check($request->get('password'), $user->password)) {

            $request->validate([
                'email' => 'required|string|email|max:255|unique:users',
            ]);

            $user->email = $request->get('email');
            $user->save();

            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'error', 'msg' => 'Invalid email or password']);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();

        if(Hash::check($request->get('old_password'), $user->password)) {

            $request->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user->password = Hash::make($request->get('password'));
            $user->save();

            return response()->json(['status' => 'ok']);
        }

        return response()->json(['status' => 'error', 'msg' => 'Invalid password']);
    }

    public function verify(Request $request, $token)
    {
        if(Auth::check()) {
            return redirect()->route('pages.home');
        }
        $user = User::where('email',$request->get('email'))->where('remember_token',$token)->first();
        if(empty($user)) {
            return redirect()->route('pages.home');
        }
        if(((time() - strtotime($user->created_at)) / 3600) > 48) {
            return redirect()->route('pages.home');
        }
        $user->remember_token = '';
        $user->status = User::USER_ACTIVE;
        $user->save();
        Auth::guard()->login($user);
        return redirect()->route('pages.home');
    }
}