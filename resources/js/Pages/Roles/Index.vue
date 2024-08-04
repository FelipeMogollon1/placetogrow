<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import RolTable from "@/Layouts/Organisms/RolTable.vue";
import UserTable from "@/Layouts/Organisms/UserTable.vue";


defineProps({
    roles: {
        type: Array,
        default: () => []
    }
});

const headers = ["name"];


</script>

<template>
    <Head :title=" $t('role.label')" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('role.label') }}</h2>
                <Link
                    v-if="can('roles.create')"
                    :href="route('roles.create')"
                    class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ $t('role.create_role') }}
                </Link>
            </div>
        </template>

        <RolTable :data="roles.data" :paginator="roles" :headers="headers" />

    </AuthenticatedLayout>
</template>

