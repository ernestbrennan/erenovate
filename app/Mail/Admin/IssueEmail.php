<?php

namespace App\Mail\Admin;

use App\Events\Mail\Admin\IssueEmailEvent;
use App\Travel\Issues\Issue;
use App\Travel\SystemNotifications\AbstractSystemNotification;
use Illuminate\Mail\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class IssueEmail implements ShouldQueue
{
    use SerializesModels;

    public $queue = 'email';

    public function handle(IssueEmailEvent $event)
    {
        $issue = $event->issue;
        $project = $issue->guarantee_project;

        \Mail::send([], [], function (Message $message) use ($issue,  $project) {

            $headers = $message->getHeaders();
            $headers->addTextHeader('X-MC-Template', 'guarantee-system-message');

            $message_header = $issue->type === Issue::TYPE_ISSUE ?
                trans("notification." . AbstractSystemNotification::TYPE_ISSUE_CREATED, ['sequence' => $issue['sequence'], 'title' => $issue['title']])  :
                'New price discrepency issue';

            $message_body = $issue->type === Issue::TYPE_ISSUE ?
                "New issue has been submitted for project - {$project->project->project_name} #{$project->id}" :
                "New price discrepency issue has been submitted for project - {$project->project->project_name} #{$project->id}";

            $headers->addTextHeader('X-MC-MergeVars', json_encode([
                'username' => 'Admin',
                'message_header' => $message_header,
                'message_body' => $message_body,
                'cta_link' => env('APP_URL') . '/admin',
            ]));

            $message->to(env('MAIL_ADMIN_EMAIL'))->subject('Project Tracker');
        });
    }
}
