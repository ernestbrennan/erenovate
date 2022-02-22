<?php

namespace App\Services;

use App\Travel\SystemNotifications\AbstractSystemNotification;

class VueRouteService
{
    public static function forNotification($notification, $user_id)
    {
        $type = $notification['type'];

        switch ($type) {
            case AbstractSystemNotification::TYPE_CONTRACT_DRAFT_OFFERED:
            case AbstractSystemNotification::TYPE_CONTRACT_DRAFT_PROPOSED:

            case AbstractSystemNotification::TYPE_CONTRACT_FIRST_ACCEPTED:
            case AbstractSystemNotification::TYPE_CONTRACT_BOTH_ACCEPTED:
            case AbstractSystemNotification::TYPE_CONTRACT_SIGNED:
            case AbstractSystemNotification::TYPE_CONTRACT_COMPLETED:

            case AbstractSystemNotification::TYPE_SUMMARY_READY:

            case AbstractSystemNotification::TYPE_INVOICE_CREATED:
            case AbstractSystemNotification::TYPE_INVOICE_UNVERIFIED:
            case AbstractSystemNotification::TYPE_INVOICE_CONFIRMED:
            case AbstractSystemNotification::TYPE_INVOICE_OVERDUE:

                $route = trans("router.$type", ['guarantee_project_id' => $notification->message->chat->guarantee_project['id']]);
                break;


            case AbstractSystemNotification::TYPE_CONTRACT_DRAFT_REJECTED:

                $route = trans("router.$type", [
                    'guarantee_project_id' => $notification->message->chat->guarantee_project['id'],
                    'draft_version' => $notification->contract_draft['version']
                ]);
                break;


            case AbstractSystemNotification::TYPE_INVOICE_COMPLETED:
            case AbstractSystemNotification::TYPE_INVOICE_REJECTED:

                $route = trans("router.$type", [
                    'guarantee_project_id' => $notification->message->chat->guarantee_project['id'],
                    'invoice_id' => $notification->invoice['id']
                ]);
                break;


            case AbstractSystemNotification::TYPE_ISSUE_CLOSED:
            case AbstractSystemNotification::TYPE_ISSUE_CREATED:
            case AbstractSystemNotification::TYPE_ADMIN_JOINED:
            case AbstractSystemNotification::TYPE_ADMIN_CLOSED:
            case AbstractSystemNotification::TYPE_ISSUE_PRICE_MODIFICATION_CREATED:

                $route = trans("router.$type", [
                    'guarantee_project_id' => $notification->message->chat->guarantee_project['id'],
                    'issue_id' => $notification->issue['id']
                ]);
                break;


            case AbstractSystemNotification::TYPE_MILESTONE_CONTENT_EDITED:
            case AbstractSystemNotification::TYPE_MILESTONE_CONTENT_ACCEPTED:
            case AbstractSystemNotification::TYPE_MILESTONE_CONTENT_REJECTED:

                $route = trans("router.$type", [
                    'guarantee_project_id' => $notification->message->chat->guarantee_project['id'],
                    'milestone_id' => $notification->milestone_content['milestone_id'],
                    'version' => $notification->milestone_content['version'],
                ]);
                break;


            default:
                $route = trans("router.default", [
                    'guarantee_project_id' => $notification->message->chat->guarantee_project['id'],
                ]);
                break;
        }

        return self::checkDeclined(self::makeUrl($route, $user_id), $notification);
    }

    public static function forProject($project_id, $type, $user_id)
    {
        switch ($type) {
            case AbstractSystemNotification::TYPE_SUMMARY_READY:
                $route = trans("router.$type", ['guarantee_project_id' => $project_id]);
                break;

            default:
                $route = trans("router.default", [
                    'guarantee_project_id' => $project_id,
                ]);
                break;

        }

        return self::makeUrl($route, $user_id);
    }

    public static function checkDeclined($route, $notification)
    {

        $type = $notification['type'];

        switch ($type) {
            case AbstractSystemNotification::TYPE_CONTRACT_DRAFT_OFFERED:
            case AbstractSystemNotification::TYPE_CONTRACT_DRAFT_PROPOSED:
        }


        return $route;
    }

    public static function forMessage($guarantee_project_id, $user_id)
    {
        $route = trans("router.default", [
            'guarantee_project_id' => $guarantee_project_id,
        ]);

        return self::makeUrl($route, $user_id);
    }

    private static function makeUrl($route, $user_id)
    {

        return env('APP_URL') . $route . '?token=' . bin2hex($user_id);
    }
}
