<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {route} from "ziggy-js";
import UserTable from "@/Layouts/Organisms/UserTable.vue";

defineProps({
    users: {
        type: Array,
        default: () => []
    }
});

const headers = ["Nombre","Email","Rol"];

</script>

<template>
    {{user}}
    <Head title="Usuarios index"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl leading-tight">Usuarios</h2>
                <Link
                    v-if="can('users.create')"
                    :href="route('users.create')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Crear una nuevo usuario
                </Link>
            </div>

            <UserTable :data="users" :headers="headers" />

        </template>
    </AuthenticatedLayout>
</template>
