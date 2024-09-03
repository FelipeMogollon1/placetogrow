<script setup>
import {Head, router, usePage} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import PaymentTable from "@/Layouts/Organisms/PaymentTable.vue";
import {computed, ref, watch} from "vue";
import {MagnifyingGlassIcon} from "@heroicons/vue/24/outline/index.js";
import { debounce } from '@/Utils/debounce.js';

defineProps({
    payments: {
        type: Object,
        default: () => []
    }
});

const search = ref(usePage().props.filter.search),
    pageNumber = ref(1);

const paymentsUrl = computed(() => {
    const url = new URL(route("payments.index"))

    if(search.value){
        url.searchParams.append("search", search.value)
    }
   return url;
});

const debouncedUpdateUrl = debounce((updatedPaymentsUrl) => {
    router.visit(updatedPaymentsUrl, {
        preserveScroll: true,
        preserveState: true,
        replace: true
    });
}, 300);

watch(
    () => paymentsUrl.value,
    (updatedPaymentsUrl) => {
        debouncedUpdateUrl(updatedPaymentsUrl);
    }
);

const headers = ["microsite_name","microsite_type","reference","payer_name", "payer_surname","currency","amount","status"];

</script>

<template>
    <Head :title="$t('microsites')" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('payment.payments_title') }}</h2>

            </div>
        </template>

        <div class="max-w-sm mx-auto relative">
            <input type="text"
                   v-model="search"
                   class="py-2 px-11 block w-full border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-full text-sm"
                   :placeholder="$t('search')">
            <MagnifyingGlassIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-6 text-gray-600" />
        </div>

        <PaymentTable :data="payments.data" :paginator="payments" :headers="headers" />
    </AuthenticatedLayout>
</template>

