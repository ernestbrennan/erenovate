import Vue from 'vue'
import VueRouter from 'vue-router'

import Login from '../components/pages/Login/Login'
import ProjectList from '../components/pages/ProjectList/ProjectList'
import IssueList from '../components/pages/IssueList/IssueList'
import Issue from '../components/pages/Issue/Issue'
import ContractSigned from '../components/pages/ContractSigned/ContractSigned'

Vue.use(VueRouter);

const router = new VueRouter({
    base: '/admin/',
    mode: 'history',
    routes: [
        {
            path:'/',
            name: 'project-list',
            component: ProjectList,
            meta: {requiresAuth: true},
        },
        {
            path:'/project/:guarantee_project_id',
            name: 'project-issues',
            component: IssueList,
            meta: {requiresAuth: true},
        },
        {
            path: '/project/:guarantee_project_id/issue/:issue_id',
            name: 'issue',
            component: Issue,
            meta: {requiresAuth: true},
        },
        {
            path: '/project/signed/:guarantee_project_id',
            name: 'project-signed',
            component:ContractSigned,
            meta: {requiresAuth: true},
        },
        {
            path:'/login',
            name: 'login',
            component: Login,
            meta: {requiresAuth: true},
        },
        // {
        //     path: '*',
        //     name: 'not-found',
        //     redirect:  { name: 'project-list' }
        // }
    ],
});

router.beforeEach(async (to, from, next) => {

    if (to.meta.requiresAuth) {

    }

    return next();
});

export default router