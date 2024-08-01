<template>
    <div class="flex flex-col my-10 mx-20">
        <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-2xl">
                    <table class="min-w-full">
                        <thead class="bg-gray-200 border-b">
                        <tr>
                            <th v-for="header in headers" :key="header" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ header }}
                            </th>
                            <th class="flex justify-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(item, index) in data" :key="index" class="transition duration-300 ease-in-out hover:bg-gray-100">
                            <template v-for="(value, key) in item">
                                <template v-if="key !== 'id' && key !== 'guard_name' && key !== 'created_at' && key !== 'updated_at'">
                                    <td :key="key" class="px-6 py-3 whitespace text-sm text-gray-900">
                                        {{ value }}
                                    </td>
                                </template>
                            </template>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="text-gray-400 flex justify-center">
                                    <Link
                                        class="m-1"
                                        v-if="can('roles.edit')"
                                        :href="route('roles.edit', item.id)"
                                    >
                                            <span class="material-symbols-outlined">
                                                <PencilIcon class="w-6 hover:text-gray-500"/>
                                            </span>
                                    </Link>
                                    <Link
                                        class="m-1"
                                        v-if="can('roles.destroy')"
                                        :href="route('roles.destroy', { id: item.id })"
                                        method="delete"
                                        as="button"
                                    >
                                            <span class="material-symbols-outlined">
                                                <TrashIcon class="w-6 hover:text-red-500" />
                                            </span>
                                    </Link>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { TrashIcon, PencilIcon } from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
    data: {
        type: Array,
        required: true
    },
    headers: {
        type: Array,
        required: true
    }
});
</script>
