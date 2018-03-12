<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Email;
use App\EmailKey;
use App\EmailSent;
use Illuminate\Http\Request;

class EmailsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $email = new Email();
        $emails = $email->getEmails($request->all());

        return view('admin.emails.list', [
            'statuses' => trans('emails.status'),
            'emails' => $emails
        ]);
    }

    public function sent(Request $request) {
        $email = new EmailSent();
        $emails = $email->getEmails($request->all());
        return view('admin.emails.sent', [
            'emails' => $emails
        ]);
    }
    public function sentView($id) {
        $email = EmailSent::findOrFail($id);
        return view('admin.emails.sentView', [
            'email' => $email
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, $id)
    {
        $email = Email::findOrFail($id);
        if ($request->isMethod('post')) {
            $email->code = $request->input('code');
            $email->subject = $request->input('subject');
            $email->content = $request->input('content');
            $email->status = $request->input('status', 0);
            $email->save();
            return redirect()->route('admin.emails');
        }

        if(!empty($email->code)) {
            $keys = EmailKey::where('code','common')->orWhere('code', $email->code)->groupBy('keyword')->get();
        } else {
            $keys = EmailKey::groupBy('keyword')->get();
        }

        return view('admin.emails.form', [
            'statuses' => trans('emails.status'),
            'email' => $email,
            'keys' => $keys
        ]);
    }
    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $email = new Email();
        if ($request->isMethod('post')) {
            $email->code = $request->input('code');
            $email->subject = $request->input('subject');
            $email->content = $request->input('content');
            $email->status = $request->input('status');
            $email->save();
            return redirect()->route('admin.emails');
        }

        $keys = EmailKey::groupBy('keyword')->get();

        return view('admin.emails.form', [
            'statuses' => trans('emails.status'),
            'email' => $email,
            'keys' => $keys
        ]);
    }

}
