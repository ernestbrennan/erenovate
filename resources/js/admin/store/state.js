export default {
    timeline: null,
    summary: {
        payments: [],
        payment_total: {
            final_work: '',
            final_material: '',
            final_total: '',
            estimated_total: ''
        }
    },
    drawer: true,
    global_loader: false,
    readOnly: false,
    containerHeight: false,
    contractHeightHeader: false,
    chatBodyHeight: false,
    dialogChatWithdraw: false,
    proposeContract: false,
    slideSearchContracts: false,
    globLoading: Boolean(false),
    errorAlert: null,
    user: {
        firstname: 'Admin',
        role: 'admin',
        photo: '/img/default_avatar.png',
    },
    commentDialog: '',
    renderDialog: false,
    dialogMain: {
        openDialog: false,
        contentObj: false,
        type: false,
        dialogFun: false,
    },

    projects: [],
    issues: [],
    issue: null,
    chat: {
        messages: [],
        homeowner: {},
        contractor: {},
        guarantee_project: {}
    },
    guarantee_project: {
        id: null,
        contractor_id: null,
        homeowner_id: null,
        chat_id: null,
        project_id: null,
        status: '',
        contractor_visited: 0,
        homeowner_visited: 0,
        contract_status: 'pending',
        project_name: '',
        price_discrepancy: null,
        issues: [
            {
                status: 'pending',
                chat_id: null,
                title: '',
                description: '',
                sequence: '',
                status: '',
                type: ''
            }
        ],
    },
    contract: {
        homeowner_info: {
            type: 'individual',
            first_name: '',
            last_name: '',
            company_name: '',
            representative_name: '',
            address_1: '',
            address_2: '',
            city: '',
            province: '',
            postal_code: '',
            confirm: 0
        },
        contractor_info: {
            first_name: '',
            last_name: '',
            company_name: '',
            representative_name: '',
            address_1: '',
            address_2: '',
            city: '',
            province: '',
            postal_code: '',
            confirm: 0,
            user: {
                role: ''
            }
        },
    },
    contract_draft: {
        title: '',
        description: '',
        type: 'single',
        status: 'new',
        homeowner_accepted: false,
        contractor_accepted: false,
        batches: [],
        milestones: [
            {
                status: 'pending',
                milestone_content: {

                    title: null,
                    description: null,
                    price: 0,
                    start_date: null,
                    end_date: null,
                    status: 'pending',
                    material_supply_side: 'contractor',
                    materials_provide_on: 'contract',
                    batches: [],
                    materials: [],
                    material_files: [],
                }
            }
        ],
        summary: {
            start_date: null,
            end_date: null,
            service_cost: 0,
            material_cost: 0,
            total_project: 0,
        }
    },
};
