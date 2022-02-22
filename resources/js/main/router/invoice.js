import InvoiceList from '../components/pages/InvoicesList/InvoiceList'
import InvoiceCreate from '../components/pages/InvoiceNew/InvoiceNew'
import InvoiceHistory from '../components/pages/InvoiceHistory/InvoiceHistory'
import InvoiceCurrent from '../components/pages/InvoiceCurrent/InvoiceCurrent'

export default [
    {
        path: '/project/:guarantee_project_id/invoices/',
        name: 'list-invoice',
        component: InvoiceList,
        meta: {requiresAuth: true},
    },
    {
        path: '/project/:guarantee_project_id/invoice/create/',
        name: 'create-invoice',
        component: InvoiceCreate,
        meta: {requiresAuth: true},
    },
    {
        path: '/project/:guarantee_project_id/invoice-history/:invoice_id',
        name: 'history-invoice',
        component: InvoiceHistory,
        meta: {requiresAuth: true},
    },
    {
        path: '/project/:guarantee_project_id/invoice/current',
        name: 'current-invoice',
        component: InvoiceCurrent,
        meta: {requiresAuth: true},
    },
]