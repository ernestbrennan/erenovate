function parseNumber(value){
    value = value.toString();
    return parseFloat(value.replace(/,/g, ''));
}
function parsePrice(price){
    let str = price.toString().split(",").join('');
    return parseFloat(str)
};
export function updateInvoiceSummary(state, type) {

    switch (type) {
        case 'materials_cost':

            let materials_cost = 0;

            state.invoice.materials.forEach((material) => {
                if (state.invoice.milestone.milestone_content.material_supply_side  === 'contractor') {
                    materials_cost += parsePrice(material.price) * material.quantity;
                }
            });

            state.invoice_summary.materials_cost = materials_cost;
            break;

        case 'works_cost':

            let works_cost = 0;

            state.invoice.works.forEach((work) => {
                works_cost += parsePrice(work.price) * work.quantity
            });

            state.invoice_summary.works_cost = works_cost;
            break;

        case 'total_price':
            state.invoice.total_price =
                Number.parseFloat(state.invoice_summary.materials_cost) +
                Number.parseFloat(state.invoice_summary.works_cost) +
                parsePrice(state.invoice.taxes);
            break;
    }
}