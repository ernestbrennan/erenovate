<?php

namespace App\Mail;

use App\Events\Mail\WithdrawProjectEmailEvent;
use Illuminate\Mail\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class WithdrawProjectEmail implements ShouldQueue
{
    use SerializesModels;

    public $queue = 'email';

    public function handle(WithdrawProjectEmailEvent $event)
    {
        $user = $event->user;
        $project_name = $event->project_name;

        \Mail::send([], [], function (Message $message) use ($user, $project_name) {

            $mailTo = env('MAIL_DEBUG', false) ? env('MAIL_DEBUG_EMAIL', 'gectorgrundy@gmil.com') : $user['email'];

            $headers = $message->getHeaders();
            $headers->addTextHeader('X-MC-Template', 'guarantee-system-message');


            $headers->addTextHeader('X-MC-MergeVars', json_encode([
                'username' => $user['firstname'] . ' ' . $user['lastname'],
                'message_header' => "Project '$project_name'' has been withdrawn",
                'message_body' => "Project '$project_name' has been withdrawn",
                'cta_link' => env('APP_URL')
            ]));

            $message->to($mailTo)->subject('Project Tracker');
        });

    }
}
