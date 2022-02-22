export default {
    read_only_milestone: true,
    current_milestone: {
        status: 'pending',
        edited: {
            is_edited: false,
            user_id: null
        },
        milestone_content: {
            title: null,
            description: null,
            price: null,
            start_date: null,
            end_date: null,
            status: 'active',
            batches: [],
            materials: [],
            material_files: [],
        }
    }
}