<script setup lang="ts">
import {onMounted, ref} from "vue";
import {api} from '@/services/api';

interface Account {
    account_number: string;
    balance: number;
}

interface Transaction {
    type: 'withdraw' | 'deposit' | string;
    type_label: string;
    amount: number;
    balance_momentary: number;
    created_at: string;
}

interface TransactionFilters {
    limit?: boolean;
    start_date?: string;
    end_date?: string;
}

const account = ref<Account | null>(null);
const transactions = ref<Transaction[]>([]);
const loadingAccount = ref(false);
const loadingTransactions = ref(false);
const transactionsError = ref<string | null>(null);
const accountError = ref<string | null>(null);

function formatCurrencyFromCents(cents: number) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(cents / 100);
}

async function fetchAccount() {
    loadingAccount.value = true;
    accountError.value = null;

    try {
        const {data} = await api.get('accounts/account')
        account.value = data.data ?? data;
        console.log(account.value);
    } catch (e: any) {
        accountError.value = e.response?.data?.message ?? 'Erro ao carregar dados.';
        alert(accountError)
    } finally {
        loadingAccount.value = false;
    }
}

async function fetchTransactions(filters: TransactionFilters = {}) {
    loadingTransactions.value = true;
    transactionsError.value = null;
    const cleanParams = Object.fromEntries(
        Object.entries(filters).filter(([, value]) => value !== '' && value !== undefined)
    );
    try {
        const {data} = await api.get('accounts/transactions', {
            params: cleanParams
        });
        transactions.value = Array.isArray(data?.data) ? data.data : [];
    } catch (e: any) {
        transactionsError.value =
            e.response?.data?.message ?? 'Erro ao carregar transações.';
    } finally {
        loadingTransactions.value = false;
    }
}

onMounted(() => {
    fetchAccount();
    fetchTransactions({limit: true})
});
</script>

<template>
    <div class="w-80">

        <div class="bg-cyan-500">
            <div v-if="loadingAccount">Carregando...</div>

            <div v-else-if="accountError">
                {{ accountError }}
            </div>

            <div v-else-if="account">
                <p>
                    <strong>Conta:</strong>
                    {{ account.account_number }}
                </p>
                <p>
                    <strong>Saldo:</strong>
                    {{ formatCurrencyFromCents(account.balance) }}
                </p>
            </div>
        </div>
        <div class="bg-cyan-950">
            <div v-if="loadingTransactions">Carregando Histórico...</div>
            <div v-else-if="transactionsError">{{ transactionsError }}</div>
            <p v-else-if="transactions.length === 0">Sem transações.</p>

            <ul v-else>
                <li
                    v-for="(transaction, index) in transactions"
                    :key="`${transaction.created_at}-${transaction.balance_momentary}-${index}`"
                >
                    <strong>{{ transaction.type_label }}</strong>
                    {{ transaction.type === 'withdraw' ? '-' : '+' }} {{ formatCurrencyFromCents(transaction.amount) }}
                    —
                    Saldo do momento: {{ formatCurrencyFromCents(transaction.balance_momentary) }} —
                    Data: {{ transaction.created_at }}
                </li>
            </ul>
        </div>
    </div>
</template>

<style scoped>

</style>
