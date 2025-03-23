import { createRouter, createWebHistory } from "vue-router";
import Home from "../views/Home.vue";
import Login from "../views/auth/Login.vue";
import Register from "../views/auth/Register.vue";
import PostList from "../views/posts/PostList.vue";
import PostDetail from "../views/posts/PostDetail.vue";
import PostCreate from "../views/posts/PostCreate.vue";
import PostEdit from "../views/posts/PostEdit.vue";
import CategoryList from "../views/categories/CategoryList.vue";

const requireAuth = (to, from, next) => {
    const token = localStorage.getItem("auth_token");
    if (!token) {
        return next({ name: "login" });
    }
    next();
};

const requireGuest = (to, from, next) => {
    const token = localStorage.getItem("auth_token");
    if (token) {
        return next({ name: "home" });
    }
    next();
};

const routes = [
    {
        path: "/",
        name: "home",
        component: Home,
    },
    {
        path: "/login",
        name: "login",
        component: Login,
        beforeEnter: requireGuest,
    },
    {
        path: "/register",
        name: "register",
        component: Register,
        beforeEnter: requireGuest,
    },
    {
        path: "/posts",
        name: "posts",
        component: PostList,
    },
    {
        path: "/posts/create",
        name: "post-create",
        component: PostCreate,
        beforeEnter: requireAuth,
    },
    {
        path: "/posts/:id",
        name: "post-detail",
        component: PostDetail,
        props: true,
    },
    {
        path: "/posts/:id/edit",
        name: "post-edit",
        component: PostEdit,
        props: true,
        beforeEnter: requireAuth,
    },
    {
        path: "/categories",
        name: "categories",
        component: CategoryList,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: "active",
});

export default router;
