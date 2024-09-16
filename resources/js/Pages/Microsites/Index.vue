<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { route } from "ziggy-js";
import SecondaryTable from "@/Layouts/Organisms/SecondaryTable.vue";

defineProps({
    microsites: {
        type: Object,
        default: () => []
    }
});

const headers = ["name","type", "category","administrator"];
</script>

<template>
    <Head :title="$t('microsites')" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('microsites') }}</h2>
                <Link v-if="can('microsites.create')"
                    :href="route('microsites.create')"
                      class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ $t('create_microsite') }}
                </Link>
            </div>
        </template>
        <SecondaryTable :data="microsites.data" :paginator="microsites" :headers="headers" />
    </AuthenticatedLayout>
</template>

