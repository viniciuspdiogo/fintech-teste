import './assets/main.css'

import { createApp } from 'vue';
import { createPinia } from 'pinia';

import App from './App.vue';
import router from './router';
import { useAuthStore } from '@/stores/auth';

import './assets/main.css';

const app = createApp(App);
app.use(createPinia());

const auth = useAuthStore();

if (auth.isAuthenticated) {
    try {
        await auth.fetchUser();
    } catch {
        auth.clear();
    }
}

app.use(router);
app.mount('#app');
