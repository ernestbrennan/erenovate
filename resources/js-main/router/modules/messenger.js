export default {
    path: '/messages/:guarantee_project_id',
    component:  () => import('@/layout/index'),
    children: [
        {
            path: '/',
            component: () => import('@/views/messenger/parts/Messages'),
            name: 'messages',
            meta: {requiresAuth: true},
        },
        {
            path: 'notes',
            component: () => import('@/views/messenger/parts/Notes'),
            name: 'notes',
            meta: {requiresAuth: true},
        },
        {
            path: 'file-history',
            component: () => import('@/views/messenger/parts/FileHistory'),
            name: 'fileHistory',
            meta: {requiresAuth: true},
        },
    ]
}
