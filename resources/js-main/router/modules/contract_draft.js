export default {
  path: '/project/:guarantee_project_id',
  component: () => import('@/layout/index'),
  children: [
    {
      path: 'new-draft',
      component: () => import('@/views/contract-draft-new/index'),
      name: 'new-draft',
      meta: {requiresAuth: true},
    },
    {
      path: 'current-draft',
      component: () => import('@/views/contract-draft-current/index'),
      name: 'current-draft',
      meta: {requiresAuth: true},
    },
    {
      path: 'history/:draft_version',
      component: () => import('@/views/contract-draft-history/index'),
      name: 'history-draft',
      meta: {requiresAuth: true},
    },
  ]
}
