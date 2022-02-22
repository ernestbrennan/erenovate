export default {
    invoice_read_only: true,
    has_current_invoice: false,
    invoices: [],
    invoice_summary: {
        works_cost: 0,
        materials_cost: 0,
        total: 0,
    },
    invoice: {
        title: '',
        description: '',
        number: '',
        taxes: 0,
        status: 'pending',
        total_price: 0,
        due_date: null,
        creation_date: null,
        materials: [],
        material_files: [],
        batches: [],
        works: [],
        milestone: {
            id: null,
            sequence: 1,
            milestone_content: {
                title: '',
                description: '',
                batches: [],
            }
        }
    },
}