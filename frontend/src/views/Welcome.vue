<script setup lang="ts">
import {reactive, ref} from 'vue';
import {useRouter} from 'vue-router';
import {useAuthStore} from '@/stores/auth';

const auth = useAuthStore();
const router = useRouter();

const loginForm = reactive({email: '', password: ''});
const loginLoading = ref(false);
const loginErrors = ref<Record<string, string[]>>({});
const loginMessage = ref<string | null>(null);

async function onLogin() {
    loginLoading.value = true;
    loginErrors.value = {};
    loginMessage.value = null;

    try {
        await auth.login(loginForm.email, loginForm.password);
        router.push('/dashboard');
    } catch (e: any) {
        loginErrors.value = e.response?.data?.errors ?? {};
        loginMessage.value = e.response?.data?.message ?? 'Erro inesperado.';
        const firstFieldError = Object.values(loginErrors.value).flat()[0];
        alert(firstFieldError || loginMessage.value);
    } finally {
        loginLoading.value = false;
    }
}


const registerForm = reactive({name: '', email: '', password: '', password_confirmation: ''});
const registerLoading = ref(false);
const registerErrors = ref<Record<string, string[]>>({});
const registerMessage = ref<string | null>(null);

async function onRegister() {
    registerLoading.value = true;
    registerErrors.value = {};
    registerMessage.value = null;

    try {
        await auth.register(
            registerForm.name,
            registerForm.email,
            registerForm.password,
        );
        router.push('/dashboard');
    } catch (e: any) {
        registerErrors.value = e.response?.data?.errors ?? {};
        registerMessage.value = e.response?.data?.message ?? 'Erro inesperado.';
        const firstFieldError = Object.values(registerErrors.value).flat()[0];
        alert(firstFieldError || registerMessage.value);
    } finally {
        registerLoading.value = false;
    }
}
</script>
<template>
    <div class="
    mt-[100px] m-auto w-full max-w-3xl p-6
    flex justify-content align-center">
        <div class="w-1/2 p-[10px] bg-neutral-700">
            <form @submit.prevent="onLogin">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-300">Acessar Conta</h2>
                    <input
                        id="email"
                        type="email"
                        v-model="loginForm.email"
                        placeholder="Insira o seu E-mail"
                        class="mt-2 w-full rounded border px-3 py-2 bg-neutral-900"
                        required
                    />
                    <input
                        id="password"
                        type="password"
                        v-model="loginForm.password"
                        placeholder="Insira a sua Senha"
                        class="mt-2 w-full rounded border px-3 py-2 bg-neutral-900 "
                        required
                    />
                </div>
                <button
                    type="submit"
                    :disabled="loginLoading"
                    class="mt-2 cursor-pointer bg-lime-800 hover:bg-lime-900 text-white font-bold py-2 px-4 border border-lime-700 rounded">
                    {{ loginLoading ? 'Aguarde...' : 'Acessar' }}
                </button>
            </form>
        </div>
        <div class=" w-1/2 p-[10px]">
            <form @submit.prevent="onRegister">
                <div>
                    <h2 class="text-lg font-semibold text-neutral-300">Criar Conta</h2>
                    <input
                        id="register-name"
                        v-model="registerForm.name"
                        placeholder="Insira o seu Nome"
                        class="mt-2 w-full rounded border px-3 py-2 bg-neutral-900 "
                        required
                    />
                    <input
                        id="register-email"
                        v-model="registerForm.email"
                        type="email"
                        placeholder="Insira o seu E-mail"
                        class="mt-2 w-full rounded border px-3 py-2 bg-neutral-900 "
                        required
                    />
                    <input
                        id="register-password"
                        v-model="registerForm.password"
                        type="password"
                        placeholder="Insira a sua Senha"
                        class="mt-2 w-full rounded border px-3 py-2 bg-neutral-900 "
                        required
                    />
                    <button
                        type="submit"
                        :disabled="registerLoading"
                        class="mt-2 cursor-pointer bg-lime-800 hover:bg-lime-900 text-white font-bold py-2 px-4 border border-lime-700 rounded">
                        {{ registerLoading ? 'Cadastrando...' : 'Criar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
