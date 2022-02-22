<?php

use App\Travel\SystemNotifications\AbstractSystemNotification;

return [
    AbstractSystemNotification::TYPE_USER_INVITE => 'Project Scope Draft #1 for ":project_name" is ready to review. You can Accept or Edit the details in the Project Scope Draft, and we’ll notify the Pro.',

    AbstractSystemNotification::TYPE_CONTRACT_DRAFT_OFFERED => 'Project Draft Scope #:version has been proposed',
    AbstractSystemNotification::TYPE_CONTRACT_DRAFT_REJECTED => 'Project Draft Scope #:version has been rejected',
    AbstractSystemNotification::TYPE_CONTRACT_DRAFT_PROPOSED => 'Project Draft Scope #:version - Different Terms have been proposed',

    AbstractSystemNotification::TYPE_CONTRACT_PROJECT_HIRE_READY => 'Your Project Tracker on eRenovate is ready',
    AbstractSystemNotification::TYPE_CONTRACT_FIRST_ACCEPTED => 'Project Scope details have been accepted by the :role_accepted. The :role_need_accept has been notified to also confirm acceptance of the Project Scope.',
    AbstractSystemNotification::TYPE_CONTRACT_BOTH_ACCEPTED => 'Project draft Scope has been accepted by both parties',
    AbstractSystemNotification::TYPE_CONTRACT_SIGNED => 'Your Project ":project_name" has been accepted by a Project Success Advisor. Your project is now ready to start!',
    AbstractSystemNotification::TYPE_CONTRACT_COMPLETED => 'Good news. The status for Project ":project_name" is now Completed. Your 88Day Workmanship Guarantee has now been activated, and your review is shown below.',

    AbstractSystemNotification::TYPE_INVOICE_CREATED => 'Payment request for milestone #:sequence  has been issued. Login to upload proof of the payment which you paid directly to the Pro.',
    AbstractSystemNotification::TYPE_INVOICE_UNVERIFIED => 'Payment request for milestone #:sequence confirmation has been declined.',
    AbstractSystemNotification::TYPE_INVOICE_CONFIRMED => 'Payment request for Milestone #:sequence has been paid and confirmed. The Pro must now verify they received your payment.',
    AbstractSystemNotification::TYPE_INVOICE_COMPLETED => 'Payment request for milestone #:sequence  has been confirmed. This completes the Milestone, or entire Project when only one Milestone exists"',
    AbstractSystemNotification::TYPE_INVOICE_REJECTED => 'Payment request for milestone #:sequence  has been declined.',
    AbstractSystemNotification::TYPE_INVOICE_OVERDUE => 'Payment request for milestone #:sequence  has been overdue.',

    AbstractSystemNotification::TYPE_MILESTONE_CONTENT_EDITED => 'Milestone #:milestone_sequence edit has been proposed ',
    AbstractSystemNotification::TYPE_MILESTONE_CONTENT_ACCEPTED => 'Milestone #:milestone_sequence edit has been accepted',
    AbstractSystemNotification::TYPE_MILESTONE_CONTENT_REJECTED => 'Milestone #:milestone_sequence edit has been declined',

    AbstractSystemNotification::TYPE_MATERIALS_CONTENT_EDITED => 'Materials edit has been proposed for Milestone #:milestone_sequence',
    AbstractSystemNotification::TYPE_MATERIALS_CONTENT_ACCEPTED => 'Materials edit has been accepted for Milestone #:milestone_sequence',
    AbstractSystemNotification::TYPE_MATERIALS_CONTENT_REJECTED => 'Materials edit has been declined for Milestone #:milestone_sequence',

    AbstractSystemNotification::TYPE_SUMMARY_READY => 'Project Milestones are completed, and a Project Summary is available for you to view',

    AbstractSystemNotification::TYPE_ISSUE_CREATED => 'Workmanship issue #:sequence has been created: :title',
    AbstractSystemNotification::TYPE_ISSUE_CLOSED => 'Workmanship issue #:sequence has been closed',
    AbstractSystemNotification::TYPE_ADMIN_JOINED => 'eRenovate Customer Care representative has responded to your Issue #:sequence',
    AbstractSystemNotification::TYPE_ADMIN_CLOSED => 'Ticket #:sequence has been closed by Support Representative',
    AbstractSystemNotification::TYPE_ISSUE_PRICE_MODIFICATION_CREATED => 'Workmanship issue #:sequence has been created: Price discrepancy issue',
];
