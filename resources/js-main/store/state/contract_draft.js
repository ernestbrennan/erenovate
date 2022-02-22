export default {
    contract_draft: {
        title: '',
        description: '',
        type: 'single',
        status: 'new',
        homeowner_accepted: false,
        contractor_accepted: false,
        batches: [],
        contract_signed: {
            description: '',
            file: null,
        },
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
    draft_history: [],
}
