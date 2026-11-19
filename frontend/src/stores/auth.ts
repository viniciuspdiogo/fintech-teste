import { defineStore } from 'pinia';
import { computed, ref } from 'vue';
import { api } from '@/services/api';

interface User {
    id: number;
    name: string;
    email: string;
}

const TOKEN_KEY = 'token';

export const useAuthStore = defineStore('auth', () => {
    const token = ref<string | null>(localStorage.getItem(TOKEN_KEY));
    const user = ref<any>(
        localStorage.getItem('user') ? JSON.parse(localStorage.getItem('user')!) : null
    );
    const isAuthenticated = computed(() => !!token.value);

    function setToken(value: string) {
        token.value = value;
        localStorage.setItem(TOKEN_KEY, value);
    }

    function setUser(value: any) {
        if (value) {
            localStorage.setItem('user', JSON.stringify(value));
        } else {
            localStorage.removeItem('user');
        }
    }

    function clear() {
        token.value = null;
        user.value = null;
        localStorage.removeItem(TOKEN_KEY);
        localStorage.removeItem('user');
    }

    async function login(email: string, password: string) {
        const { data } = await api.post('/login', { email, password });
        setToken(data.data.token);
        setUser(data.data.user);
    }

    async function register(
        registerName: string,
        registerEmail: string,
        registerPassword: string,
    ) {
        const { data } = await api.post('/register', {
            registerName,
            registerEmail,
            registerPassword,
        });
        setToken(data.data.token);
        user.value = data.data.user ?? null
        setUser(user.value);
    }

    async function fetchUser() {
        const { data } = await api.get('/user');
        user.value = data;
    }

    async function logout() {
        try {
            await api.post('/logout');
        } finally {
            clear();
        }
    }

    return { token, user, isAuthenticated, login, register, fetchUser, logout, clear };
});
