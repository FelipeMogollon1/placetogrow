<script setup>
import { defineProps } from 'vue';
import {Link} from "@inertiajs/vue3";
import {route} from "ziggy-js";

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

<template>
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-200 border-b">
                        <tr>
                            <th v-for="header in headers" :key="header" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ header }}
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(item, index) in data" :key="index" class="transition duration-300 ease-in-out hover:bg-gray-100">

                            <template v-for="(value, key) in item">

                                <template v-if="key !== 'id'">
                                    <td :key="key" class="px-6 py-3 whitespace text-sm text-gray-900">
                                        {{ value }}
                                    </td>
                                </template>
                            </template>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                               <div class="space-x-4">
                                   <Link
                                       :href="route('categories.edit',item.id)"
                                       class="inline-flex items-center px-2 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                   >
                                       <span class="material-symbols-outlined">
                                            edit
                                        </span>
                                   </Link>
                                   <Link
                                       :href="route('categories.destroy',item.id)"
                                       method="delete"
                                       as="button"
                                       class="inline-flex items-center px-2 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                   >
                                       <span class="material-symbols-outlined">
                                             delete
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


