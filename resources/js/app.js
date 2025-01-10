import '../css/app.css';
import 'bootstrap/dist/css/bootstrap.css';
import './bootstrap';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from 'ziggy-js';
import Home from './Pages/Home.vue'; // Import the component
import UserPosts from "./Pages/UserPosts.vue";
import Create from "./Pages/Create.vue"; // Import the component
import Post from "./Pages/Post.vue";
import router from "./routes";
import Edit from "./Pages/Edit.vue"; // Import the component
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`, // Ensure it's resolving from the Pages directory
            import.meta.glob('./Pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(router)

        return app.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});


createApp(Home).mount("#home");
createApp(UserPosts).mount("#usersPosts");
createApp(Create).mount("#create");
createApp(Post).use(router).mount("#post");
createApp(Edit).use(router).mount("#edit");
