export const NotificationTypes = {
  data() {
    return {
      types: {
        contract_first_accepted: 'contract_first_accepted',
        contract_both_accepted: 'contract_both_accepted',
        contract_signed: 'contract_signed',
        contract_completed: 'contract_completed',
        
        contract_draft_offered: 'contract_draft_offered',
        contract_draft_rejected: 'contract_draft_rejected',
        contract_draft_proposed: 'contract_draft_proposed',
        
        milestone_content_edited: 'milestone_content_edited',
        milestone_content_accepted: 'milestone_content_accepted',
        milestone_content_rejected: 'milestone_content_rejected',
        
        invoice_created: 'invoice_created',
        invoice_unverified: 'invoice_unverified',
        invoice_confirmed: 'invoice_confirmed',
        invoice_completed: 'invoice_completed',
        invoice_rejected: 'invoice_rejected',
        invoice_overdue: 'invoice_overdue',
        
        summary_ready: 'summary_ready',
        
        issue_created: 'issue_created',
        issue_closed: 'issue_closed',
        issue_admin_joined: 'issue_admin_joined',
        issue_admin_closed: 'issue_admin_closed',
        issue_price_modification_created: 'issue_price_modification_created',
        
        user_invite: 'user_invite'
      },
    }
  },
  computed: {
    classType() {
      const type = this.message.notification.type
      
      if (type === 'contract_draft_offered' ||
        type === 'contract_draft_proposed' ||
        type === 'contract_signed' ||
        type === 'invoice_created' ||
        type === 'contract_completed' ||
        type === 'milestone_content_edited' ||
        type === 'summary_ready' ||
        type === 'issue_created' ||
        type === 'issue_admin_joined' ||
        type === 'issue_admin_closed' ||
        type === 'user_invite'
      ) {
        return 'proposed'
      } else if (type === 'contract_draft_rejected' ||
        type === 'invoice_rejected' ||
        type === 'issue_closed' ||
        type === 'invoice_overdue' ||
        type === 'invoice_unverified' ||
        type === 'milestone_content_rejected') {
        return 'rejected'
      } else if (type === 'contract_first_accepted' ||
        type === 'contract_both_accepted' ||
        type === 'invoice_confirmed' ||
        type === 'contract_first_accepted' ||
        type === 'contract_both_accepted' ||
        type === 'invoice_completed' ||
        type === 'milestone_content_accepted' ||
        type === 'issue_price_modification_created'
      ) {
        return 'accepted'
      }
    },
  },
  methods: {
    checkType(type) {
      if (type === 'contract') {
        return [
          this.types.contract_first_accepted,
          this.types.contract_both_accepted,
          this.types.contract_signed,
          this.contract_completed
        ]
          .includes(this.message.notification.type)
      } else if (type === 'contract_draft') {
        return [
          this.types.contract_draft_offered,
          this.types.contract_draft_proposed,
        ]
          .includes(this.message.notification.type)
      } else if (type === 'milestone_content') {
        return [
          this.types.milestone_content_edited,
          this.types.milestone_content_accepted,
          this.types.milestone_content_rejected,
        ]
          .includes(this.message.notification.type)
      } else if (type === 'invoice') {
        return [
          this.types.invoice_created,
          this.types.invoice_confirmed,
        ]
          .includes(this.message.notification.type)
      } else if (type === 'issue') {
        return [
          this.types.issue_created,
          this.types.issue_closed,
          this.types.issue_admin_joined,
          this.types.issue_admin_closed,
          this.types.issue_price_modification_created,
        ]
          .includes(this.message.notification.type)
      } else if (type === 'summary') {
        return [
          this.types.summary_ready,
        ]
          .includes(this.message.notification.type)
      } else if (type === 'user') {
        return [
          this.types.user_invite,
        ]
          .includes(this.message.notification.type)
      }
    },
  },
};

export const IconByNotification = {
  methods: {
    iconNotification(notification, color = 'white') {
      const type = notification.type;
      
      if (type === 'contract_draft_offered' ||
        type === 'invoice_created' ||
        type === 'issue_created' ||
        type === 'user_invite'
      ) {
        return `plus_${color}.svg`
      } else if (type === 'contract_both_accepted' ||
        type === 'contract_first_accepted' ||
        type === 'milestone_content_accepted' ||
        type === 'contract_signed' ||
        type === 'invoice_confirmed' ||
        type === 'issue_admin_joined' ||
        type === 'issue_price_modification_created'
      
      ) {
        return `check_${color}.svg`
      } else if (
        type === 'contract_signed' ||
        type === 'invoice_completed' ||
        type === 'invoice_completed' ||
        type === 'summary_ready' ||
        type === 'contract_completed'
      ) {
        return `double-check-mark_${color}.svg`
      } else if (type === 'milestone_content_edited' ||
        type === 'contract_draft_proposed'
      ) {
        return `pen_${color}.svg`
      } else if (type === 'invoice_unverified' ||
        type === 'contract_draft_rejected' ||
        type === 'invoice_rejected' ||
        type === 'invoice_overdue' ||
        type === 'milestone_content_rejected' ||
        type === 'issue_admin_closed'
      
      ) {
        return `close__md_${color}.svg`
      }
    },
  }
};
