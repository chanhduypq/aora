<?php

namespace App\Http\Controllers;

use App\Mail\Feedback;
use Illuminate\Http\Request;
use Mail;

class ToolsController extends Controller
{
    /**
     * @param Request $request
     */
    public function feedback(Request $request)
    {
        Mail::to(config('settings.admin_email'))->send(new Feedback($request->all()));

        return redirect()->route('pages.contact');
    }
}