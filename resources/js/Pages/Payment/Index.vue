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

            </div>
        </template>
        <PaymentTable :data="payments.data" :paginator="payments" :headers="headers" />
    </AuthenticatedLayout>
</template>

