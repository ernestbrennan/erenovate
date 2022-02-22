export default {
  path: '/settings',
  component:  () => import('@/layout/index'),
  children: [
    {
      path: '/',
      component: () => import('@/views/settings/index'),
      name: 'settings',
    }
  ]
}
