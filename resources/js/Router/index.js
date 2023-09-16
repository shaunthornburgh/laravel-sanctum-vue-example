import { createRouter, createWebHistory } from "vue-router";

import Login from "../Pages/Auth/Login.vue"
import Register from "../Pages/Auth/Register.vue";
import ForgotPassword from "../Pages/Auth/ForgotPassword.vue";
import ResetPassword from "../Pages/Auth/ResetPassword.vue";
import { useUserStore } from "../Store/user";
import AuthLayout from "../Layouts/AuthLayout.vue";
import AppLayout from "../Layouts/AppLayout.vue";
import Dashboard from "../Pages/App/Dashboard.vue";

const routes = [
    {
        path: "/",
        redirect: "/auth/login",
    },
    {
        path: "/auth",
        name: "auth",
        component: AuthLayout,
        beforeEnter: (to, from, next) => {
            useUserStore().user.id ? next({name: "app.dashboard"}) : next()
        },
        children: [
            {
                path: 'login',
                component: Login,
                name: 'auth.login'
            },
            {
                path: "register",
                component: Register,
                name: 'auth.register'
            },
            {
                path: "forgot-password",
                component: ForgotPassword,
                name: 'auth.forgot-password'
            },
            {
                path: "reset-password",
                component: ResetPassword,
                name: 'auth.reset-password'
            },
        ],
    },
    {
        path: "/app",
        beforeEnter: (to, from, next) => {
            useUserStore().user.id ? next() : next({ name: 'auth.login' })
        },
        name: "app",
        component: AppLayout,
        children: [
            {
                path: "",
                name: "app.dashboard",
                component: Dashboard,
            },

        ]
    }
];

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 }
    },
    routes,
});

export default router;
