<script setup>
import { Head, Link } from '@inertiajs/vue3';
import {defineProps} from 'vue';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import RolTable from "@/Layouts/Organisms/RolTable.vue";


defineProps({
    roles: {
        type: Array,
        default: () => []
    }
});

const headers = ["Nombre"];


</script>

<template>
    <Head title="Roles Index" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Roles</h2>
                <Link
                    :href="route('roles.create')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Crear un nuevo rol
                </Link>
            </div>
        </template>

        <RolTable :data="roles" :headers="headers" />

    </AuthenticatedLayout>
</template>

