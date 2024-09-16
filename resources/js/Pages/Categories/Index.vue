<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import PrimaryTable from "@/Layouts/Organisms/PrimaryTable.vue";

defineProps({
    categories: {
        type: Array,
        default: () => []
    }
});

const headers = ["name","description"];

</script>

<template>
    <Head :title=" $t('category.label')"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('category.label') }}</h2>
                <Link
                    v-if="can('categories.create')"
                    :href="route('categories.create')"
                    class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ $t('category.create_category') }}
                </Link>
            </div>
        </template>
        <PrimaryTable :data="categories.data" :paginator="categories" :headers="headers" />
    </AuthenticatedLayout>
</template>
