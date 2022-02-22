export default {
  path: '/project/:guarantee_project_id',
  component: () => import('@/layout/index'),
  children: [
    {
      path: 'invoices',
      component: () => import('@/views/invoices-list/index'),
      name: 'list-invoice',
      meta: {requiresAuth: true},
    },
    {
      path: 'invoice/create',
      component: () => import('@/views/invoice-new/index'),
      name: 'create-invoice',
      meta: {requiresAuth: true},
    },
    {
      path: 'invoice-history/:invoice_id',
      component: () => import('@/views/invoice-history/index'),
      name: 'history-invoice',
      meta: {requiresAuth: true},
    },
    {
      path: 'invoice/current',
      component: () => import('@/views/invoice-current/index'),
      name: 'current-invoice',
      meta: {requiresAuth: true},
    },
  ]
}
