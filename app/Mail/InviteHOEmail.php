<?php

namespace App\Mail;

use App\Events\Mail\InviteHOEmailEvent;
use Illuminate\Mail\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class InviteHOEmail implements ShouldQueue
{
    use SerializesModels;

    public $queue = 'email';

    public function handle(InviteHOEmailEvent $event)
    {
        $guarantee_project = $event->guarantee_project;
        $homeowner_recently_created = $event->homeowner_recently_created;

        \Mail::send([], [], function (Message $message) use ($guarantee_project, $homeowner_recently_created) {

            $contractor = $guarantee_project->contractor;
            $homeowner = $guarantee_project->homeowner;

            $homeowner_name = $homeowner['firstname'] . ' ' . $homeowner['lastname'];
            $contractor_name = $contractor['firstname'] . ' ' . $contractor['lastname'];
            $mailTo = env('MAIL_DEBUG', false) ? env('MAIL_DEBUG_EMAIL', 'gectorgrundy@gmil.com') : $homeowner['email'];

            $headers = $message->getHeaders();
            $headers->addTextHeader('X-MC-Template', 'guarantee-without-button');

            $text = trans('project.invite_' . ($homeowner_recently_created ? 'new' : 'old'), [
                'contractor_name' => $contractor_name,
                'contractor_business_name' => $contractor->user_business_map->business_profile['businessName'],
                'project_name' => $guarantee_project->project['project_name'],
                'homeowner_email' => $homeowner['email'],
                'homeowner_password' => $homeowner['password'],
            ]);

            $headers->addTextHeader('X-MC-MergeVars', json_encode([
                'username' => $homeowner_name,
                'message_header' => '',
                'message_body' => $text,
                'cta_link' => env('APP_URL')
            ]));

            $message->to($mailTo)->subject('Project Tracker');
        });
    }
}
