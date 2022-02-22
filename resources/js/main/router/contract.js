import ProjectsList from '../components/pages/PojectsList/ProjectsList'
import ContractSigned from '../components/pages/ContractSigned/ContractSigned'
import ContractHistory from '../components/pages/ContractHistory/ContractHistory'

export default [
    {
        path: '/projects',
        name: 'projects-list',
        component: ProjectsList,
    },
    {
        path: '/project/:guarantee_project_id/signed',
        name: 'contract-signed',
        component: ContractSigned,
        meta: {requiresAuth: true},
    },
    {
        path: '/project/:guarantee_project_id/history',
        name: 'contract-history',
        component: ContractHistory,
        meta: {requiresAuth: true},
    },
]