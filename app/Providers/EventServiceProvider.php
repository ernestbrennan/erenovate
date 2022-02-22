<?php

namespace App\Providers;

use App\Events\ContractCompletedEvent;
use App\Events\ContractSignedEvent;
use App\Events\CreatedMessageEvent;
use App\Events\Mail\Admin\IssueEmailEvent;
use App\Events\Mail\InviteHOEmailEvent;
use App\Events\Mail\NewMessageEmailEvent;
use App\Events\Mail\PayFeeEmailEvent;
use App\Events\Mail\SystemNotificationEmailEvent;
use App\Events\Mail\WithdrawProjectEmailEvent;
use App\Fcm\NewMessageFcm;
use App\Listeners\Erenovate\ContractCompletedErenovateRequest;
use App\Listeners\Erenovate\ContractSignedErenovateRequest;
use App\Mail\Admin\IssueEmail;
use App\Mail\ContractCompletedEmail;
use App\Mail\InviteHOEmail;
use App\Mail\NewMessageEmail;
use App\Mail\PayFeeEmail;
use App\Mail\SystemNotificationEmail;
use App\Mail\WithdrawProjectEmail;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        SystemNotificationEmailEvent::class => [
            SystemNotificationEmail::class,
        ],
        PayFeeEmailEvent::class => [
            PayFeeEmail::class
        ],
        NewMessageEmailEvent::class => [
            NewMessageEmail::class,
        ],
        WithdrawProjectEmailEvent::class => [
            WithdrawProjectEmail::class
        ],
        ContractCompletedEvent::class => [
            ContractCompletedEmail::class,
            ContractCompletedErenovateRequest::class
        ],
        ContractSignedEvent::class => [
            ContractSignedErenovateRequest::class
        ],
        InviteHOEmailEvent::class => [
            InviteHOEmail::class
        ],
        CreatedMessageEvent::class => [
            NewMessageFcm::class,
        ],
        IssueEmailEvent::class => [
            IssueEmail::class
        ]
    ];

    public function boot()
    {
        parent::boot();
    }
}
