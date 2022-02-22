import Milestone from "../components/pages/Milestone/Milestone";

export default [
    {
        path: '/project/:guarantee_project_id/milestone/current',
        name: 'current-milestone',
        component: Milestone,
        meta: {requiresAuth: true},
    },
    {
        path: '/project/:guarantee_project_id/milestone/:milestone_id/:version?',
        name: 'by-id-milestone',
        component: Milestone,
        meta: {requiresAuth: true},
    },
];