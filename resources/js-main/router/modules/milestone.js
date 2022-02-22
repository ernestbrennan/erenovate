export default {
  path: '/project/:guarantee_project_id',
  component: () => import('@/layout/index'),
  children: [
    {
      path: 'milestone/current',
      component: () => import('@/views/milestone/index'),
      name: 'current-milestone',
      meta: {requiresAuth: true},
    },
    {
      path: 'milestone/:milestone_id/:version?',
      component: () => import('@/views/milestone/index'),
      name: 'by-id-milestone',
      meta: {requiresAuth: true},
    },
  ]
}
