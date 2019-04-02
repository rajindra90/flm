import VueRouter from 'vue-router'

// Pages
import Register from './components/Pages/Register'
import Login from './components/Pages/Login'
import LogOut from './components/Pages/LogOut'
import FriendsList from './components/Pages/FriendsList'
import NotFound from './components/Pages/404'
import Confirm from './components/Pages/Confirm'
import NotConfirm from './components/Pages/NotConfirm'


const routes = [
    {
        path: '/',
        name: 'login',
        component: Login,
        meta: {
            requiresVisitor: true
        }
    },{
        path: '/logout',
        name: 'logout',
        component: LogOut,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: {
            requiresVisitor: true
        }
    },
    {
        path: '/friendslist',
        name: 'friendslist',
        component: FriendsList,
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '*',
        name: 'not-found',
        component: NotFound,
    },
    {
        path: '/confirm',
        name: 'confirm',
        component: Confirm,
    },
    {
        path: '/notconfirm',
        name: 'notconfirm',
        component: NotConfirm,
    }
];

const router = new VueRouter({
    routes,
});

export default router