<?php

namespace App\Http\Controllers;
use App\Mail\OrderReserved;
use App\Mail\WelcomeMail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    //
    public function email()
    {
        //$target_email = 'fresbluehman@hotmail.com';
        $target_email = 'beautisong@hotmail.com';
        $content = [
            'name' => 'you',
            'subject' => 'subject',
            'body' => 'body'
        ];
        Mail::to($target_email)->send(new OrderReserved($content));
        return (new OrderReserved($content));
    }
}

