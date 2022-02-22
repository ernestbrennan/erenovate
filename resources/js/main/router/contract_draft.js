import ContractDraftNew from '../components/pages/ContractDraftNew/ContractDraftNew'
import ContractDraftCurrent from '../components/pages/ContractDraftCurrent/ContractDraftCurrent'
import ContractDraftHistory from '../components/pages/ContractDraftHistory/ContractDraftHistory'

export default [
    {
        path: '/project/:guarantee_project_id/new-draft',
        name: 'new-draft',
        component: ContractDraftNew,
        meta: {requiresAuth: true},
    },
    {
        path: '/project/:guarantee_project_id/current-draft',
        name: 'current-draft',
        component: ContractDraftCurrent,
        meta: {requiresAuth: true},
    },
    {
        path: '/project/:guarantee_project_id/history/:draft_version',
        name: 'history-draft',
        component: ContractDraftHistory,
        meta: {requiresAuth: true},
    },
]