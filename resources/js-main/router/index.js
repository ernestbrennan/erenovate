import Vue from 'vue'
import VueRouter from 'vue-router'

import store from '@/store/index'
import Layout from "@/layout/index";

import settings_module from './modules/setting'
import messenger_module from './modules/messenger'
import project_module from './modules/project'
import contract_draft from './modules/contract_draft'
import milestone from './modules/milestone'
import invoice from './modules/invoice'

import {getOrCreateProject, checkVisitedProject} from '@/api/project'

Vue.use(VueRouter);

VueRouter.prototype.toProjects = function () {
  this.push({name: 'projects-list'})
};

VueRouter.prototype.toProjectMessenger = function () {
  this.push({
    name: 'messages',
    params: {
      guarantee_project_id: this.currentRoute.params.guarantee_project_id
    }
  });
};

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      redirect: {name: 'projects-list'},
      props: true
    },
    {
      path: '/sign-in',
      name: 'sign-in',
      component: () => import('@/views/sign-in/index'),
    },
    {
      path: '/sign-up',
      name: 'sign-up',
      component: () => import('@/views/sign-up/index'),
    },
    {
      path: '/projects',
      component: Layout,
      children: [
        {
          path: '/',
          component: () => import('@/views/project-list/index'),
          name: 'projects-list',
        }
      ]
    },
    {
      path: '/welcome/:guarantee_project_id',
      component: Layout,
      children: [
        {
          path: '/',
          component: () => import('@/views/welcome/index'),
          name: 'welcome',
        }
      ]
    },
    {
      path: '/archived-drafts',
      component: Layout,
      children: [
        {
          path: '/',
          component: () => import('@/views/archived-drafts-list/index'),
          name: 'archived-drafts-list',
        }
      ]
    },
    {
      path: '/invite-ho/:archived_draft_id?',
      component: Layout,
      children: [
        {
          path: '/',
          component: () => import('@/views/invite-ho/index'),
          name: 'invite-ho',
        }
      ]
    },
    settings_module,
    messenger_module,
    project_module,
    contract_draft,
    milestone,
    invoice,
    {
      path: '/hire',
      name: 'hire',
    },
    {
      path: '*',
      name: 'not-found',
      redirect: {name: 'projects-list'}
    },
  ],
});

router.beforeEach(async (to, from, next) => {
  if (to.path === '/hire' && to.query.project_id && to.query.contractor_id) {
    getOrCreateProject(to.query.project_id, to.query.contractor_id).then(response => {
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

function checkVisited(id) {
  return new Promise(resolve => {
    checkVisitedProject(id).then(response => {
      if (response.data.visited) {
        localStorage.setItem('is_visited_' + id + '_' + store.state.user.role, true);
      }
      resolve(response.data.visited);
    });
  });
}

export default router
