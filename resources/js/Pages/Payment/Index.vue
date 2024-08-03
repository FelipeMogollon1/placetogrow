<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { route } from "ziggy-js";
import PaymentTable from "@/Layouts/Organisms/PaymentTable.vue";

defineProps({
    payments: {
        type: Object,
        default: () => []
    }
});

const headers = ["microsite_name","reference","payer_name", "payer_surname","currency","amount","status"];

</script>

<template>
    <Head :title="$t('microsites')" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('payment.payments_title') }}</h2>
                <Link v-if="can('microsites.create')"
                      :href="route('microsites.create')"
                      class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ $t('create_microsite') }}
                </Link>
            </div>
        </template>
        <PaymentTable :data="payments.data" :paginator="payments" :headers="headers" />
    </AuthenticatedLayout>
</template>

