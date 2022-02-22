<?php

use App\Travel\SystemNotifications\AbstractSystemNotification;

return [
    AbstractSystemNotification::TYPE_CONTRACT_DRAFT_OFFERED => '/project/:guarantee_project_id/current-draft',
    AbstractSystemNotification::TYPE_CONTRACT_DRAFT_PROPOSED => '/project/:guarantee_project_id/current-draft',

    AbstractSystemNotification::TYPE_CONTRACT_FIRST_ACCEPTED => '/project/:guarantee_project_id/current-draft',
    AbstractSystemNotification::TYPE_CONTRACT_BOTH_ACCEPTED => '/project/:guarantee_project_id/current-draft',
    AbstractSystemNotification::TYPE_CONTRACT_SIGNED => '/project/:guarantee_project_id/signed',

    AbstractSystemNotification::TYPE_MILESTONE_CONTENT_EDITED => '/project/:guarantee_project_id/milestone/:milestone_id/:version',
    AbstractSystemNotification::TYPE_MILESTONE_CONTENT_ACCEPTED => '/project/:guarantee_project_id/milestone/:milestone_id/:version',
    AbstractSystemNotification::TYPE_MILESTONE_CONTENT_REJECTED => '/project/:guarantee_project_id/milestone/:milestone_id/:version',

    AbstractSystemNotification::TYPE_SUMMARY_READY => '/project/:guarantee_project_id/summary',
    AbstractSystemNotification::TYPE_CONTRACT_COMPLETED => '/project/:guarantee_project_id/summary',

    AbstractSystemNotification::TYPE_INVOICE_CREATED => '/project/:guarantee_project_id/invoice/current',
    AbstractSystemNotification::TYPE_INVOICE_UNVERIFIED => '/project/:guarantee_project_id/invoice/current',
    AbstractSystemNotification::TYPE_INVOICE_CONFIRMED => '/project/:guarantee_project_id/invoice/current',
    AbstractSystemNotification::TYPE_INVOICE_OVERDUE => '/project/:guarantee_project_id/invoice/current',

    AbstractSystemNotification::TYPE_CONTRACT_DRAFT_REJECTED => '/project/:guarantee_project_id/history/:draft_version',

    AbstractSystemNotification::TYPE_INVOICE_COMPLETED => '/project/:guarantee_project_id/invoice-history/:invoice_id',
    AbstractSystemNotification::TYPE_INVOICE_REJECTED => '/project/:guarantee_project_id/invoice-history/:invoice_id',

    AbstractSystemNotification::TYPE_ISSUE_CREATED => '/project/:guarantee_project_id/issue/:issue_id',
    AbstractSystemNotification::TYPE_ISSUE_CLOSED => '/project/:guarantee_project_id/issue/:issue_id',
    AbstractSystemNotification::TYPE_ADMIN_JOINED => '/project/:guarantee_project_id/issue/:issue_id',
    AbstractSystemNotification::TYPE_ADMIN_CLOSED => '/project/:guarantee_project_id/issue/:issue_id',
    AbstractSystemNotification::TYPE_ISSUE_PRICE_MODIFICATION_CREATED => '/project/:guarantee_project_id/issue/:issue_id',


    'default' => '/messages/:guarantee_project_id'
];