export default {
    drawer(state, val) {
        state.drawer = val
    },
    setContHeight(state, val) {
        state.containerHeight = val
    },

    setDialog(state, {dialog_state,dialog_model, dialog_type, dialog_fun}) {
        console.log(dialog_state,dialog_model, dialog_type);
        state.dialogMain.contentObj = dialog_model;
        state.dialogMain.openDialog = dialog_state;
        state.dialogMain.type = dialog_type;
        state.dialogMain.dialogFun = dialog_fun;
    },
    renderDialog(state, val) {
        state.renderDialog = val
    },

    newDraftDialog(state, {type, data}) {
        state.contractNewDraftDialog[type] = data
    },
    set(state, {type, data}) {
        state[type] = data
    },
    addMessage(state, message) {

        var exists = state.chat.messages.find(function (item) {
            return item.id === message.id;
        });

        if (!exists) {

            state.chat.total_message_count += 1;
            state.chat.messages.unshift(message);
        }
    },
    updateDraftSummary(state, type) {
        const g_parsePrice = (price) => {
            let str = price.toString().split(",").join('');
            return parseFloat(str)
        };
        switch (type) {
            case 'material_cost':

                let material_cost = 0;

                if (state.contract_draft.type === 'single') {

                    state.contract_draft.milestones[0].milestone_content.materials.forEach((material) => {
                        if (state.contract_draft.milestones[0].milestone_content.material_supply_side === 'contractor' && state.contract_draft.milestones[0].milestone_content.materials_provide_on === 'contract') {
                            material_cost += g_parsePrice(material.price) * material.quantity;
                        }
                    });


                    state.contract_draft.summary.material_cost = material_cost.toFixed(2);
                    break;
                }

                state.contract_draft.milestones.map((item) => {

                    item.milestone_content.materials.forEach((material) => {
                        if (item.milestone_content.material_supply_side === 'contractor'  && state.contract_draft.milestones[0].milestone_content.materials_provide_on === 'contract') {
                            material_cost += g_parsePrice(material.price) * material.quantity;
                        }
                    })
                });

                state.contract_draft.summary.material_cost = material_cost.toFixed(2);
                break;

            case 'service_cost':

                if (state.contract_draft.type === 'single') {

                    state.contract_draft.summary.service_cost = g_parsePrice(state.contract_draft.milestones[0].milestone_content.price);
                    break;
                }

                let service_cost = 0;

                state.contract_draft.milestones.map((milestone) => {

                    service_cost += g_parsePrice(milestone.milestone_content.price);
                });

                state.contract_draft.summary.service_cost = service_cost.toFixed(2);
                break;

            case 'total_project_price':
                let total_price = 0;
                total_price = g_parsePrice(state.contract_draft.summary.service_cost) + g_parsePrice(state.contract_draft.summary.material_cost);
                state.contract_draft.summary.total_project = total_price.toFixed(2);
                break;
            case 'start_date':

                let start_date = null;

                state.contract_draft.milestones.map((milestone) => {

                    let milestone_start_date = new Date(milestone.milestone_content.start_date).getTime();
                    let start_date_time = new Date(start_date).getTime();

                    if (start_date_time > milestone_start_date || !start_date) {

                        start_date = milestone.milestone_content.start_date
                    }
                });

                state.contract_draft.summary.start_date = start_date;
                break;

            case 'end_date':

                let end_date = null;

                state.contract_draft.milestones.map((milestone) => {

                    let milestone_end_date = new Date(milestone.milestone_content.end_date).getTime();
                    let end_date_time = new Date(end_date).getTime();

                    if (end_date_time < milestone_end_date || !end_date) {

                        end_date = milestone.milestone_content.end_date
                    }
                });

                state.contract_draft.summary.end_date = end_date;
                break;
        }
    }
}