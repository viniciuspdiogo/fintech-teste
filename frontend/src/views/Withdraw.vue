<script setup lang="ts">
import {onMounted, ref} from "vue";
import {api} from "@/services/api";
import {useRouter} from 'vue-router';
import AccountBalanceCard from '@/components/AccountBalanceCard.vue';
import {formatMoney, parseCents} from "@/utils/formatters";
import {computed} from 'vue';

const cents = defineModel<number>({default: 0});
const display = computed(() => formatMoney(cents.value));

const router = useRouter();
const loading = ref(false);
const errors = ref<Record<string, string[]>>({});
const message = ref<string | null>(null);

async function onWithdraw() {
    loading.value = true;
    errors.value = {};
    message.value = null;

    try {
        const {data} = await api.post('/accounts/withdraw', {amount: cents.value});
        alert(data.message);
        router.push({name: 'Dashboard'});
    } catch (e: any) {
        errors.value = e.response?.data?.errors ?? {};
        message.value = e.response?.data?.message ?? 'Erro inesperado.';
        alert(message.value);
    } finally {
        loading.value = false;
    }
}

function onInput(event: Event) {
    const input = event.target as HTMLInputElement;

    cents.value = parseCents(input.value);
    input.value = formatMoney(cents.value);
}
</script>

<template>
    <div class="mt-[100px] m-auto w-[350px]">
        <AccountBalanceCard/>
        <RouterLink :to="{ name: 'Dashboard' }"> <- Voltar</RouterLink>
        <div>
            <form @submit.prevent="onWithdraw"
                  class="flex flex-col items-end mt-3">
                <div class="flex items-center gap-2">
                    <span class="text-neutral-400">R$</span>
                    <input
                        id="amount"
                        inputmode="numeric"
                        placeholder="0,00"
                        :value="display"
                        @input="onInput"
                        class="w-1/2 bg-neutral-50 rounded-sm"
                    />
                </div>
                <button
                    type="submit"
                    :disabled="loading || cents <= 0"
                    class="w-full rounded-sm bg-red-700 px-3 py-1.5 text-sm font-medium text-white hover:bg-red-600 mt-2 cursor-pointer"
                >
                    {{ loading ? "Sacando" : "Sacar" }}
                </button>
            </form>
        </div>
    </div>
</template>
