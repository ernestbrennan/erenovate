export default {
  path: '/project/:guarantee_project_id',
  component:  () => import('@/layout/index'),
  children: [
    {
      path: 'signed',
      component: () => import('@/views/contract-signed/index'),
      name: 'contract-signed',
      meta: {requiresAuth: true},
    },
    {
      path: 'history',
      component: () => import('@/views/contract-history/index'),
      name: 'contract-history',
      meta: {requiresAuth: true},
    },
    {
      path: 'summary',
      component: () => import('@/views/summary/index'),
      name: 'summary',
      meta: {requiresAuth: true},
    },
    {
      path: 'issue/:issue_id',
      component: () => import('@/views/issue/index'),
      name: 'issue',
      meta: {requiresAuth: true},
    },
  ]
}
