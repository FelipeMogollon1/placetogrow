<script setup>
import {Head, router, usePage} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {MagnifyingGlassIcon} from "@heroicons/vue/24/outline/index.js";
import SubscriptionTable from "@/Layouts/Organisms/SubscriptionTable.vue";
import {computed, ref, watch} from "vue";
import {debounce} from "@/Utils/debounce.js";
import {route} from "ziggy-js";

const headers = [
    "reference",
    "subscriptionPlans",
    "name",
    "surname",
    "date",
    "status",
    "microsite"
];

defineProps({
    subscriptions: {
        type: Object,
        default: () => []
    }
});

const search = ref(usePage().props.filter.search),
    pageNumber = ref(1);

const paymentsUrl = computed(() => {
    const url = new URL(route("subscriptions.index"))

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

</script>

<template>
    <Head :title="$t('microsites')" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('subscription.subscriptions') }}</h2>
            </div>
        </template>

        <div class="max-w-sm mx-auto relative">
            <input type="text"
                v-model="search"
                class="py-2 px-11 block w-full border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-full text-sm"
                :placeholder="$t('search')">
            <MagnifyingGlassIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-6 text-gray-600" />
        </div>

        <SubscriptionTable :data="subscriptions.data" :paginator="subscriptions" :headers="headers" />

    </AuthenticatedLayout>
</template>

