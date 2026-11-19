<script setup lang="ts">
import {ref, computed, onMounted} from 'vue';
import {api} from '@/services/api';
import {formatCurrencyFromCents} from "@/utils/formatters";


const accountCode = ref('');
const balanceInCents = ref(0);
const loadingAccount = ref(true);
const accountError = ref<string | null>(null);
const userName = ref<string | null>(null);
const storage = localStorage.getItem("user")
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

const formattedBalance = computed(() => formatCurrencyFromCents(balanceInCents.value));

onMounted(fetchAccountBalance);
defineExpose({refresh: fetchAccountBalance});
</script>

<template>
    <div v-if="!loadingAccount">
        <span class="font-semibold text-neutral-50">
            <strong>Bem-Vindo:</strong> {{userName}}
        </span>
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
