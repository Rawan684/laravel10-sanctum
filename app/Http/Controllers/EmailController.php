<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\emailMailable;

class EmailController extends Controller
{
    public function send()
    {
        Mail::to(Auth::user()->email)->send(new emailMailable());
        return response()->json('success');
    }
}
