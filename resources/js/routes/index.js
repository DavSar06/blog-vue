import { createRouter, createWebHistory } from 'vue-router';
import Home from '../Pages/Home.vue';
import Post from '../Pages/Post.vue';
import Edit from "../Pages/Edit.vue";

const routes = [
    { path: '/', name: 'Home', component: Home },
    { path: '/post/:id', name: 'Post', component: Post },
    { path: '/post/:id/edit', name: 'Edit', component: Edit },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
