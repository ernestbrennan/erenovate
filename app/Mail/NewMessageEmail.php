<?php

namespace App\Mail;

use App\Events\Mail\NewMessageEmailEvent;
use App\Services\VueRouteService;
use App\Travel\Users\User;
use Illuminate\Mail\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class NewMessageEmail implements ShouldQueue
{
    use SerializesModels;

    public $queue = 'email';

    public function handle(NewMessageEmailEvent $event)
    {
        $chat_message = $event->message;
        $sender_role = $chat_message->sender['role'];

        $guarantee_project = $chat_message->chat->guarantee_project;

        $user = $sender_role === User::ROLE_HOMEOWNER ? $guarantee_project->contractor : $guarantee_project->homeowner;

        \Mail::send([], [], function (Message $message) use ($user, $chat_message, $guarantee_project) {

            $mailTo = env('MAIL_DEBUG', false) ? env('MAIL_DEBUG_EMAIL', 'gectorgrundy@gmil.com') : $user->email;

            $headers = $message->getHeaders();
            $headers->addTextHeader('X-MC-Template', 'guarantee-system-message');

            $headers->addTextHeader('X-MC-MergeVars', json_encode([
                'username' => $user['firstname'] . ' ' . $user['lastname'],
                'message_header' => 'New message',
                'message_body' => $chat_message['content'],
                'cta_link' => VueRouteService::forMessage($guarantee_project->id, $user->userId)
            ]));

            $message->to($mailTo)->subject('Project Tracker');
        });
    }
}
