import VueRouter from "vue-router";
import Vue from "vue";

Vue.use(VueRouter)

export default new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: () => import("@/views/Home")
        },
        {
            path: '/animes',
            component: () => import("@/views/Animes")
        },
        {
            path: '/anime/:id',
            component: () => import("@/views/Anime")
        },
        {
            path: '/privacy',
            component: () => import("@/views/CGU")
        },
        {
            path: '/:catchAll(.*)',
            component: () => import("@/views/NotFound")
        },
    ],
    scrollBehavior() {
        return {x: 0, y: 0};
    }
})