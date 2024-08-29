<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\VerificationCodeGenerated;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerificationCodeEmail;

class SendVerificationCodeToUser
{
    /**
     * Create the event listener.
     */
    public function __construct(VerificationCodeGenerated $event)
    {
        $user = $event->user;
        $code = $event->code;

        // Send the verification code to the user via email
        Mail::to($user->email)->send(new VerificationCodeEmail($code));
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        //
    }
}
