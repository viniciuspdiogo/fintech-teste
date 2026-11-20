<script setup lang="ts">
import {ref, computed, onMounted} from 'vue';
import {api} from '@/services/api';
import {formatCurrencyFromCents} from "@/utils/formatters";
import {useAuthStore} from "@/stores/auth.ts";
import {useRouter} from "vue-router";


const logoutLoading = ref(false);
const auth = useAuthStore();
const router = useRouter();

const accountCode = ref('');
const balanceInCents = ref(0);
const loadingAccount = ref(true);
const accountError = ref<string | null>(null);
const userName = ref<string | null>(null);
const storage = localStorage.getItem("user");

if (storage) {
    try {
        userName.value = JSON.parse(storage).name;
    } catch {
        userName.value = null;
    }
}
const fetchAccountBalance = async () => {
    loadingAccount.value = true;
    accountError.value = null;

    try {
        const {data} = await api.get('accounts/account');
        accountCode.value = data.data.account_number;
        balanceInCents.value = data.data.balance;
    } catch (e: any) {
        accountError.value = e.response?.data?.message ?? 'Erro ao carregar dados.';
    } finally {
        loadingAccount.value = false;
    }
}

const logout = async () => {
    logoutLoading.value = true;
    try {
        await auth.logout();
        await router.replace('/');
    } catch {
        alert('A sessão foi encerrada neste dispositivo, mas não foi possível invalidar o token no servidor.');
        await router.replace('/');
    } finally {
        logoutLoading.value = false;
    }
}

const formattedBalance = computed(() => formatCurrencyFromCents(balanceInCents.value));

onMounted(fetchAccountBalance);
defineExpose({refresh: fetchAccountBalance});
</script>

<template>
    <div v-if="!loadingAccount">
        <div class="flex justify-between items-end mb-1 font-semibold text-neutral-50">
            <div>
                <strong>Bem-Vindo:</strong> {{ userName }}
            </div>
            <div>
                <button
                    type="button"
                    :disabled="logoutLoading"
                    @click="logout"
                    class="
                    rounded-sm
                    bg-neutral-800
                    px-3
                    py-1.5
                    text-sm
                    font-medium
                    text-neutral-200
                    cursor-pointer
                    hover:bg-neutral-700
                    "
                >
                    {{ logoutLoading ? 'Saindo...' : 'Sair' }}
                </button>
            </div>
        </div>
    </div>
    <div class="border-b bg-lime-900 rounded-t-sm text-neutral-50 p-1">
        <div v-if="loadingAccount">Carregando...</div>

        <div v-else-if="accountError">
            {{ accountError }}
        </div>

        <div v-else class="flex flex-col items-end">
            <p>
                <strong>Conta:</strong>
                {{ accountCode }}
            </p>
            <p>
                <strong>Saldo:</strong>
                {{ formattedBalance }}
            </p>
        </div>
    </div>
</template>
