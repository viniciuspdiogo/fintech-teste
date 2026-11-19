<script setup lang="ts">
import {onMounted, ref} from "vue";
import {api} from '@/services/api';
import AccountBalanceCard from '@/components/AccountBalanceCard.vue';
import {formatCurrencyFromCents} from "@/utils/formatters";
import {RouterLink} from 'vue-router';

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

const filters = ref<TransactionFilters>({
    limit: true,
    start_date: '',
    end_date: '',
})


const transactions = ref<Transaction[]>([]);
const loading = ref(false);
const filterError = ref<string | null>(null);
const isWithdraw = (transaction) => transaction.type === 'withdraw';

const buildParams = (): TransactionFilters => {
    const params: TransactionFilters = {}

    params.limit = filters.value.limit;
    if (filters.value.start_date) params.start_date = filters.value.start_date;
    if (filters.value.end_date) params.end_date = filters.value.end_date;

    return params
}

const fetchTransactions = async (validate = false) => {
    filterError.value = null

    const {start_date, end_date} = filters.value
    filters.value.limit = !start_date && !end_date

    if (validate) {
        if (!start_date || !end_date) {
            alert('Ambas as datas precisam ser preenchidas');
            return
        }

        if (end_date < start_date) {
            alert('A data final não pode ser anterior à data inicial.');
            return
        }
    }

    loading.value = true;
    try {
        const {data} = await api.get('accounts/transactions', {
            params: buildParams(),
        })
        transactions.value = Array.isArray(data) ? data : (data.data ?? [])
    } catch (e: any) {
        filterError.value = e.response?.data?.message ?? 'Erro ao carregar transações.'
    } finally {
        loading.value = false
    }
}

const clearFilters = () => {
    filters.value = {
        limit: true,
        start_date: '',
        end_date: '',
    }
    filterError.value = null
    fetchTransactions()
}

onMounted(fetchTransactions)
</script>

<template>
    <div class="mt-[100px] m-auto w-[350px]">
        <AccountBalanceCard/>
        <div class="mt-2 flex flex-row content-center gap-2">
            <RouterLink
                :to="{ name: 'Deposit' }"
                class="rounded-sm bg-lime-700 px-3 py-1.5 text-sm font-medium text-white hover:bg-lime-600 w-1/2"
            >
                Depositar
            </RouterLink>
            <RouterLink
                :to="{ name: 'Withdraw' }"
                class="rounded-sm bg-red-700 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-600 w-1/2"
            >
                Sacar
            </RouterLink>
        </div>
        <div class="text-neutral-50 ">
            <div v-if="loading">Carregando Histórico...</div>
            <div v-else-if="filterError">{{ filterError }}</div>
            <p v-else-if="transactions.length === 0">
                Sem transações.
                <button
                    type="button"
                    @click="clearFilters"
                    class="rounded-sm bg-neutral-800 px-3 py-1.5 text-xs font-medium text-neutral-200 hover:bg-neutral-700"
                >
                    Recarregar histórico
                </button>
            </p>
            <div v-else class="mt-3">
                <form
                    @submit.prevent="fetchTransactions(true)"
                    class="flex flex-wrap items-end gap-3 rounded-sm border border-neutral-700 bg-neutral-900 p-3 text-neutral-100"
                >
                    <div class="flex flex-col gap-1">
                        <label for="start-date" class="text-xs text-neutral-300">Data inicial</label>
                        <input
                            id="start-date"
                            v-model="filters.start_date"
                            type="date"
                            :max="endDate || undefined"
                            class="rounded-sm border border-neutral-600 bg-neutral-800 px-2.5 py-1.5 text-sm text-white"
                        />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label for="end-date" class="text-xs text-neutral-300">Data final</label>
                        <input
                            id="end-date"
                            v-model="filters.end_date"
                            type="date"
                            :min="startDate || undefined"
                            class="rounded-sm border border-neutral-600 bg-neutral-800 px-2.5 py-1.5 text-sm text-white"
                        />
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="submit"
                            class="rounded-sm bg-lime-700 px-3 py-1.5 text-sm font-medium text-white hover:bg-lime-600 cursor-pointer"
                        >
                            Filtrar
                        </button>
                        <button
                            type="button"
                            @click="clearFilters"
                            class="rounded-sm border border-neutral-600 px-3 py-1.5 text-sm text-neutral-300 hover:bg-neutral-800 cursor-pointer"
                        >
                            Limpar
                        </button>
                    </div>
                    <p v-if="filterError" class="w-full text-xs text-red-400">
                        {{ filterError }}
                        <button
                            type="button"
                            @click="clearFilters"
                            class="rounded-sm bg-neutral-800 px-3 py-1.5 text-xs font-medium text-neutral-200 hover:bg-neutral-700"
                        >
                            Recarregar histórico
                        </button>
                    </p>
                </form>
                <p class="font-bold">Transações</p>
                <li
                    v-for="(transaction, index) in transactions"
                    :key="`${transaction.created_at}-${transaction.balance_momentary}-${index}`"
                    class="flex flex-col items-end gap-1 mb-2 p-2 border-b"
                >
                    <span
                        :class="isWithdraw(transaction) ? 'text-red-500' : 'text-lime-600'"
                        class=" rounded-sm p-1 font-bold"
                    >
                        {{ isWithdraw(transaction) ? '-' : '+' }}
                        {{ formatCurrencyFromCents(transaction.amount) }}
                    </span>
                    <span>Saldo: {{ formatCurrencyFromCents(transaction.balance_momentary) }}</span>
                    <span class="font-thin">{{ transaction.created_at }}</span>
                </li>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
