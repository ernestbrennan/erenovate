<?php

namespace App\Travel\SystemNotifications;

use App\Travel\SystemNotifications\Interfaces\SystemNotificationInterface;

abstract class AbstractSystemNotification extends SystemNotification implements SystemNotificationInterface
{
    const TYPE_CONTRACT_DRAFT_OFFERED = 'contract_draft_offered';
    const TYPE_CONTRACT_DRAFT_REJECTED = 'contract_draft_rejected';
    const TYPE_CONTRACT_DRAFT_PROPOSED = 'contract_draft_proposed';

    const TYPE_CONTRACT_PROJECT_HIRE_READY = 'contract_project_hire_ready';
    const TYPE_CONTRACT_FIRST_ACCEPTED = 'contract_first_accepted';
    const TYPE_CONTRACT_BOTH_ACCEPTED = 'contract_both_accepted';
    const TYPE_CONTRACT_SIGNED = 'contract_signed';
    const TYPE_CONTRACT_COMPLETED = 'contract_completed';

    const TYPE_INVOICE_CREATED = 'invoice_created';
    const TYPE_INVOICE_UNVERIFIED = 'invoice_unverified';
    const TYPE_INVOICE_CONFIRMED = 'invoice_confirmed';
    const TYPE_INVOICE_COMPLETED = 'invoice_completed';
    const TYPE_INVOICE_REJECTED = 'invoice_rejected';
    const TYPE_INVOICE_OVERDUE = 'invoice_overdue';

    const TYPE_MILESTONE_CONTENT_EDITED = 'milestone_content_edited';
    const TYPE_MILESTONE_CONTENT_ACCEPTED = 'milestone_content_accepted';
    const TYPE_MILESTONE_CONTENT_REJECTED = 'milestone_content_rejected';

    const TYPE_MATERIALS_CONTENT_EDITED = 'materials_content_edited';
    const TYPE_MATERIALS_CONTENT_ACCEPTED = 'materials_content_accepted';
    const TYPE_MATERIALS_CONTENT_REJECTED = 'materials_content_rejected';

    const TYPE_SUMMARY_READY = 'summary_ready';

    const TYPE_ISSUE_CREATED = 'issue_created';
    const TYPE_ISSUE_CLOSED = 'issue_closed';
    const TYPE_ADMIN_JOINED = 'issue_admin_joined';
    const TYPE_ADMIN_CLOSED = 'issue_admin_closed';
    const TYPE_ISSUE_PRICE_MODIFICATION_CREATED = 'issue_price_modification_created';

    const TYPE_USER_INVITE = 'user_invite';

    const CONTRACT = [
        self::TYPE_CONTRACT_FIRST_ACCEPTED,
        self::TYPE_CONTRACT_BOTH_ACCEPTED,
        self::TYPE_CONTRACT_SIGNED,
        self::TYPE_CONTRACT_COMPLETED,
        self::TYPE_CONTRACT_PROJECT_HIRE_READY
    ];

    const CONTRACT_DRAFT = [
        self::TYPE_CONTRACT_DRAFT_OFFERED,
        self::TYPE_CONTRACT_DRAFT_PROPOSED,
        self::TYPE_CONTRACT_DRAFT_REJECTED
    ];

    const MILESTONE_CONTENT = [
        self::TYPE_MILESTONE_CONTENT_EDITED,
        self::TYPE_MILESTONE_CONTENT_ACCEPTED,
        self::TYPE_MILESTONE_CONTENT_REJECTED
    ];

    const MATERIALS_CONTENT = [
        self::TYPE_MATERIALS_CONTENT_EDITED,
        self::TYPE_MATERIALS_CONTENT_ACCEPTED,
        self::TYPE_MATERIALS_CONTENT_REJECTED
    ];

    const INVOICE = [
        self::TYPE_INVOICE_CREATED,
        self::TYPE_INVOICE_COMPLETED,
        self::TYPE_INVOICE_CONFIRMED,
        self::TYPE_INVOICE_UNVERIFIED,
        self::TYPE_INVOICE_OVERDUE,
        self::TYPE_INVOICE_REJECTED
    ];

    const SUMMARY = [
        self::TYPE_SUMMARY_READY
    ];

    const ISSUE = [
        self::TYPE_ISSUE_CREATED,
        self::TYPE_ISSUE_CLOSED,
        self::TYPE_ADMIN_JOINED,
        self::TYPE_ADMIN_CLOSED,
        self::TYPE_ISSUE_PRICE_MODIFICATION_CREATED,
    ];

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope(function ($query) {

            $query->where('type', static::getType());
        });
    }

    protected static function saveSystemNotification($createParams)
    {
        return self::query()->create($createParams);
    }
}
