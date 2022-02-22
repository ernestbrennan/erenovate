import Messages from '../components/pages/Messenger/parts/Messages'
import Notes from '../components/pages/Messenger/parts/Notes'
import FileHistory from '../components/pages/Messenger/parts/FileHistory'

export default [
    {
        path: '/messages/:guarantee_project_id',
        name: 'messages',
        component: Messages,
        children: [],
        meta: {requiresAuth: true},
    },
    {
        path: '/messages/:guarantee_project_id/notes',
        name: 'notes',
        component: Notes,
        meta: {requiresAuth: true},
    },
    {
        path: '/messages/:guarantee_project_id/file-history',
        name: 'fileHistory',
        component: FileHistory,
        meta: {requiresAuth: true},
    },
]