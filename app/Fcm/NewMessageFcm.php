<?php

namespace App\Fcm;

use App\Events\CreatedMessageEvent;
use App\Events\Mail\NewMessageEmailEvent;
use App\Services\VueRouteService;
use App\Travel\Users\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class NewMessageFcm implements ShouldQueue
{
    use SerializesModels;

    public $queue = 'fcm';

    public function handle(CreatedMessageEvent $event)
    {
        $message = $event->message;

        $guarantee_project = $message->chat->guarantee_project;

        if (!$guarantee_project) {
            return;
        }
        $user = $message->sender['role'] === User::ROLE_HOMEOWNER ? $guarantee_project->contractor : $guarantee_project->homeowner;
//        $user = $message->sender;

        $title = 'New Message';

        if (!empty($guarantee_project->project['project_name'])) {
            $title .= ' from <' . $guarantee_project->project['project_name'] . '> project';
        }
        $text = $message->content;
        $click_action = VueRouteService::forMessage($guarantee_project->id, $user->userId);


        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60 * 20);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($text)
            ->setSound('default')
            ->setClickAction($click_action)
            ->setIcon('https://www.erenovate.com/homeowners/assets/homepage/images/ereno_logoshield_vert-homepage-3.png');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData([
            'title' => $title,
            'body' => $text,
            'icon' => "https://www.erenovate.com/homeowners/assets/homepage/images/ereno_logoshield_vert-homepage-3.png",
            'image' => "https://www.erenovate.com/homeowners/assets/homepage/images/ereno_logoshield_vert-homepage-3.png",
            'click_action' => $click_action
        ]);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        if ($user->fcm) {

            try {
                FCM::sendTo($user->fcm->fcm_token, $option, $notification, $data);
            } catch (\Exception $exception) {

            }
        }
    }
}
