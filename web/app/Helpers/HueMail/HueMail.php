<?php

namespace App\Helpers\HueMail;

use Mail;

class HueMail
{
    public function sendMail($to, $name, $subject, $msg)
    {
        Mail::raw($msg, function ($message) use ($to, $name, $subject) {
            $message->to($to, $name);
            $message->subject($subject);
        });
    }
}
