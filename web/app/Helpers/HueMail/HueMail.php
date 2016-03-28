<?php

declare(strict_types=1);

namespace App\Helpers\HueMail;

use Mail;

class HueMail
{
    public function sendMail(string $to, string $name, string $subject, string $msg)
    {
        Mail::raw($msg, function ($message) use ($to, $name, $subject) {
            $message->to($to, $name);
            $message->subject($subject);
        });
    }

    public function sendConfirmation(string $username, string $email, string $confirmation)
    {
        Mail::send('templates/mails/confirmation', [
            'name' => $username,
            'link' => $confirmation,
        ], function ($message) use ($email, $username) {
            $message->to($email, $username);
            $message->subject('Please confirm your email');
        });
    }
}
