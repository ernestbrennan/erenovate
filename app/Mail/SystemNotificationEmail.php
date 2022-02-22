<?php

namespace App\Mail;

use App\Events\Mail\SystemNotificationEmailEvent;
use App\Services\VueRouteService;
use App\Travel\SystemNotifications\AbstractSystemNotification;
use Illuminate\Mail\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class SystemNotificationEmail implements ShouldQueue
{
    use SerializesModels;

    public $queue = 'email';

    public function handle(SystemNotificationEmailEvent $event)
    {
        $notification = $event->notification;

        $contractor = $notification->message->chat->guarantee_project->contractor;
        $homeowner = $notification->message->chat->guarantee_project->homeowner;

        $is_from_except_array = in_array($notification['type'], [
            AbstractSystemNotification::TYPE_INVOICE_CREATED,
        ]);

        $is_for_interlocutor_array = in_array($notification['type'],[
            AbstractSystemNotification::TYPE_MILESTONE_CONTENT_EDITED,
            AbstractSystemNotification::TYPE_MILESTONE_CONTENT_ACCEPTED,
            AbstractSystemNotification::TYPE_MILESTONE_CONTENT_REJECTED,
            AbstractSystemNotification::TYPE_MATERIALS_CONTENT_EDITED,
            AbstractSystemNotification::TYPE_MATERIALS_CONTENT_ACCEPTED,
            AbstractSystemNotification::TYPE_MATERIALS_CONTENT_REJECTED,
            AbstractSystemNotification::TYPE_CONTRACT_FIRST_ACCEPTED
        ]);

        if ($is_for_interlocutor_array) {

            if ($homeowner['userId'] !== $notification->message['sender_id']) {
                $this->sendMail($notification, $homeowner);
            }

            if ($contractor['userId'] !== $notification->message['sender_id']) {
                $this->sendMail($notification, $contractor);
            }


        } else {

            $this->sendMail($notification, $homeowner);

            if (!$is_from_except_array) {
                $this->sendMail($notification, $contractor);
            }
        }
    }

    public function sendMail($notification, $user)
    {
        \Mail::send([], [], function (Message $message) use ($notification, $user) {

            $mailTo = env('MAIL_DEBUG', false) ? env('MAIL_DEBUG_EMAIL', 'gectorgrundy@gmil.com') : $user['email'];

            $headers = $message->getHeaders();
            $headers->addTextHeader('X-MC-Template', 'guarantee-system-message');

            $content = $notification->message['content'];

            $headers->addTextHeader('X-MC-MergeVars', json_encode([
                'username' => $user['firstname'] . ' ' . $user['lastname'],
                'message_header' => $notification->getTitle('email'),
                'message_body' => empty($content) ? $content : "User Comment: $content",
                'cta_link' => VueRouteService::forNotification($notification, $user['userId'])
            ]));

            $message->to($mailTo)->subject('Project Tracker');
        });
    }
}
