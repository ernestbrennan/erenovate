<?php

namespace App\Mail;

use App\Events\Mail\PayFeeEmailEvent;
use Illuminate\Mail\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;

class PayFeeEmail implements ShouldQueue
{
    use SerializesModels;

    public $queue = 'email';

    public function handle(PayFeeEmailEvent $event)
    {
        $contractor = $event->guarantee_project->contractor;
        $is_extra_fee = $event->is_extra_fee;
        $summary = $event->guarantee_project->contract->last_draft->summary;

        $fee = calculate_fee(format_price($summary['service_cost']) + format_price($summary['material_cost']));

        $content = [
            'header' => 'Project has been accepted by both parties. Please pay the fee to start the project.',
            'body' => "$ $fee",
            'link' => env('APP_URL') . '/project/' . $event->guarantee_project['id'] . '/current-draft?token=' . bin2hex($contractor->user['userId'])

        ];

        if ($is_extra_fee) {
            $content = [
                'header' => 'Additional Guarantee Project Fee payment is required.',
                'body' => 'The customer has changed project status to completed. However, the Final Project Price differs from the initial Project Price. The client and is awaiting you to pay this additional fee so that their 88 Day Workmanship Guarantee can begin.',
                'link' => env('APP_URL') . '/project/' . $event->guarantee_project['id'] . '/summary?token=' . bin2hex($contractor->user['userId'])
            ];
        }

        \Mail::send([], [], function (Message $message) use ($contractor, $content) {

            $mailTo = env('MAIL_DEBUG', false) ? env('MAIL_DEBUG_EMAIL', 'gectorgrundy@gmil.com') : $contractor['email'];

            $headers = $message->getHeaders();
            $headers->addTextHeader('X-MC-Template', 'guarantee-system-message');

            $headers->addTextHeader('X-MC-MergeVars', json_encode([
                'username' => $contractor['firstname'] . ' ' . $contractor['lastname'],
                'message_header' => $content['header'],
                'message_body' => $content['body'],
                'cta_link' => $content['link']
            ]));

            $message->to($mailTo)->subject('Project Tracker');
        });
    }
}
