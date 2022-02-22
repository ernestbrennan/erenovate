<?php

namespace App\Mail;

use App\Events\ContractCompletedEvent;
use App\Services\VueRouteService;
use App\Travel\SystemNotifications\AbstractSystemNotification;
use Illuminate\Mail\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class ContractCompletedEmail implements ShouldQueue
{
    use SerializesModels;

    public $queue = 'email';

    public function handle(ContractCompletedEvent $event)
    {
        $guarantee_project = $event->guarantee_project;
        $homeowner = $event->guarantee_project->project->homeowner;

        \Mail::send([], [], function (Message $message) use ($homeowner, $guarantee_project) {

            $mailTo = env('MAIL_DEBUG', false) ? env('MAIL_DEBUG_EMAIL', 'gectorgrundy@gmil.com') : $homeowner['email'];

            $headers = $message->getHeaders();
            $headers->addTextHeader('X-MC-Template', 'guarantee-system-message');

            $headers->addTextHeader('X-MC-MergeVars', json_encode([
                'username' => $homeowner['firstname'] . ' ' . $homeowner['lastname'],
                'message_header' => 'Waiting for Project Success Advisor to review Project Summary',
                'message_body' => "After the Project Summary is viewed,  your Project status will change to Completed and your eRenovate 88 Day Workmanship Guarantee will begin.",
                'cta_link' => VueRouteService::forProject($guarantee_project['id'], AbstractSystemNotification::TYPE_SUMMARY_READY, $homeowner['userId']),
            ]));

            $message->to($mailTo)->subject('Project Tracker');
        });
    }
}
