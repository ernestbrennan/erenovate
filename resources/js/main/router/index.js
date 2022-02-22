import Vue from 'vue'
import VueRouter from 'vue-router'

import Welcome from '../components/pages/Welcome/Welcome'
import Summary from '../components/pages/Summary/Summary'
import invoice from './invoice'
import message from './message'
import contract from './contract'
import contract_draft from './contract_draft'
import milestone from './milestone'
import Issue from '../components/pages/Issue/Issue'
import Settings from '../components/pages/Settings/Settings'
import InviteHO from '../components/pages/InviteHO/InviteHO'
import SignIn from '../components/pages/SignIn/SignIn'
import SignUp from '../components/pages/SignUp/SignUp'
import ArchivedDraftList from '../components/pages/ArchivedDraftsList/ArchivedDraftList'
import store from '../store/index'

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            redirect: {name: 'projects-list'},
            props: true
        },
        {
            path: '/welcome/:project_id',
            name: 'welcome',
            component: Welcome,
        },
        {
            path: '/project/:guarantee_project_id/summary',
            name: 'summary',
            component: Summary,
            meta: {requiresAuth: true},
        },

        {
            path: '/project/:guarantee_project_id/issue/:issue_id',
            name: 'issue',
            component: Issue,
            meta: {requiresAuth: true},
        },
        {
            path: '/settings',
            name: 'settings',
            component: Settings,
        },
        {
            path: '/sign-in',
            name: 'sign-in',
            component: SignIn,
        },
        {
            path: '/sign-up',
            name: 'sign-up',
            component: SignUp,
        },
        {
            path: '/invite-ho/:archived_draft_id?',
            name: 'invite-ho',
            component: InviteHO,
        },
        {
            path: '/archived-drafts',
            name: 'archived-drafts-list',
            component: ArchivedDraftList,
        },

        ...message,
        ...contract,
        ...contract_draft,
        ...milestone,
        ...invoice,

        {
            path: '/hire',
            name: 'hire',
        },
        {
            path: '*',
            name: 'not-found',
            redirect: {name: 'projects-list'}
        }
    ],
});

router.beforeEach(async (to, from, next) => {

    if (to.path === '/hire' && to.query.project_id && to.query.contractor_id) {

        api
            .post('project.getOrCreate', {
                project_id: to.query.project_id,
                contractor_id: to.query.contractor_id
            })
            .then(response => {

                next({
                    name: 'messages',
                    params: {
                        guarantee_project_id: response.data.guarantee_project.id
                    }
                })
            });
    }
    if (to.meta.requiresAuth) {

        const is_visited = localStorage.getItem('is_visited_' + to.params.guarantee_project_id + '_' + store.state.user.role);

        if (!is_visited) {

            checkVisited(to.params.guarantee_project_id).then(visited => {

                if (!visited) {
                    return next({
                        name: 'welcome',
                        params: {
                            project_id: to.params.guarantee_project_id
                        }
                    });
                }
            });
        }
    }

    return next();
});

function checkVisited(project_id) {

    return new Promise(resolve => {

        api.get('project.checkVisited', {
            params:{
                id: project_id
            }
        })
            .then(response => {
                if (response.data.visited) {
                    localStorage.setItem('is_visited_' + project_id + '_' + store.state.user.role, true);
                }
                resolve(response.data.visited);
            });
    });
}

export default router
