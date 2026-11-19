import {computed} from "vue";

export function formatCurrencyFromCents(cents: number) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(cents / 100);
}


const formatter = new Intl.NumberFormat('pt-BR', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
});

const MAX_DIGITS = 12;
export function formatMoney(cents: number): string {
    return formatter.format(cents / 100);
}

export function parseCents(raw: string): number {
    const digits = raw.replace(/\D/g, '').slice(0, MAX_DIGITS);
    return digits ? parseInt(digits, 10) : 0;
}
