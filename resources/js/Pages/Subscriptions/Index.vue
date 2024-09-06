<script setup>
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {MagnifyingGlassIcon} from "@heroicons/vue/24/outline/index.js";
import SubscriptionTable from "@/Layouts/Organisms/SubscriptionTable.vue";

const headers = ["name","surname", "description","reference"];

defineProps({
    subscriptions: {
        type: Object,
        default: () => []
    }
});

</script>

<template>
    <Head :title="$t('microsites')" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('subscription.subscriptions') }}</h2>
            </div>
        </template>

      <!--- <div class="max-w-sm mx-auto relative">
            <input type="text"
                   v-model="search"
                   class="py-2 px-11 block w-full border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-full text-sm"
                   :placeholder="$t('search')">
            <MagnifyingGlassIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-6 text-gray-600" />
        </div>
       ---->

        <SubscriptionTable :data="subscriptions.data" :paginator="subscriptions" :headers="headers" />

    </AuthenticatedLayout>
</template>

