<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = new User();
        $data = $request->all();
        if(!isset($data['status']) || $data['status'] == '') {
            $data['status'] = array(User::USER_ACTIVE, User::USER_INACTIVE);
        } else {
            $data['status'] = array($data['status']);
        }

        if(!isset($data['is_admin']) || $data['is_admin'] == '') {
            $data['is_admin'] = 0;
        }

        $users = $user->getUsers($data);
        $status = trans('users.status');
        unset($status[2]);

        return view('admin.users.list', [
            'statuses' => $status,
            'users' => $users,
            'total_admin' => $user->getCountUsers(array('is_admin'=>1,'status'=>array(User::USER_ACTIVE, User::USER_INACTIVE))),
            'total_users' => $user->getCountUsers(array('is_admin'=>0,'status'=>array(User::USER_ACTIVE, User::USER_INACTIVE)))
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deleted(Request $request)
    {
        $data = $request->all();
        $data['status'] = array(User::USER_DELETED);
        $user = new User();
        $users = $user->getUsers($data);

        return view('admin.users.deleted', [
            'statuses' => array(),
            'users' => $users
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $user = user::findOrFail($id);
        if ($request->isMethod('post')) {
            $user->name = $request->input('name');
            $user->email = $request->input('name');
            $user->phone = $request->input('phone');
            $user->address = $request->input('address');
            $user->postal_code = $request->input('postal_code');
            $user->is_admin = $request->input('is_admin', 0);
            $user->status = $request->input('status', 0);
            if ($request->hasFile('avatar')) {
                if(!file_exists(public_path('uploads/users'))) {
                    mkdir(public_path('uploads/users'));
                }
                $image = $request->file('avatar');
                $filename  = time() . '.' . $image->getClientOriginalExtension();
                $path = public_path('uploads/users');
                $image->move($path, $filename);
                $user->avatar = $filename;
            }
            $user->save();
            return redirect()->route('admin.users.view', ['id' => $user->id]);
        }

        return view('admin.users.form', [
            'statuses' => trans('users.status'),
            'user' => $user
        ]);
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $user = new user();
        if ($request->isMethod('post')) {
            $user->name = $request->input('name');
            $user->email = $request->input('name');
            $user->phone = $request->input('phone');
            $user->address = $request->input('address');
            $user->postal_code = $request->input('postal_code');
            $user->is_admin = $request->input('is_admin', 0);
            $user->status = $request->input('status', 0);
            if ($request->hasFile('avatar')) {
                if(!file_exists(public_path('uploads/users'))) {
                    mkdir(public_path('uploads/users'));
                }
                $image = $request->file('avatar');
                $filename  = time() . '.' . $image->getClientOriginalExtension();
                $path = public_path('uploads/users');
                $image->move($path, $filename);
                $user->avatar = $filename;
            }
            $user->save();
            return redirect()->route('admin.users.view', ['id' => $user->id]);
        }
        return view('admin.users.form', [
            'statuses' => trans('users.status'),
            'user' => $user
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($id)
    {
        $user = User::findorfail($id);
        return view('admin.users.view',['user'=>$user,'statuses' => trans('orders.status')]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete($id) : JsonResponse
    {
        $user = User::findorfail($id);
        $me = Auth::user();
        if($user->id == $me->id) {
            return response()->json(array(
                'success' => false,
                'message' => 'You can not delete yourself'
            ));
        }
        $user->status = User::USER_DELETED;
        $user->save();
        return response()->json(['success' => true]);
    }
}
