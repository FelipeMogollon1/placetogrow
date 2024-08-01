<script setup>
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { TrashIcon, PencilIcon, EyeIcon } from '@heroicons/vue/24/outline';
import Paginator from '@/Components/Paginator.vue';
import { PhotoIcon } from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
    data: {
        type: Array,
        required: true
    },
    headers: {
        type: Array,
        required: true
    },
    paginator: {
        type: Object,
        required: true
    }
});

</script>

<template>
    <div class="flex flex-col mt-8">
        <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-2xl border border-1 shadow">
                    <table class="min-w-full">
                        <thead class="bg-gray-200 border-b">
                        <tr>
                            <th v-for="header in headers" :key="header" scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $t(`microsites_table.${header}`) }}
                            </th>
                            <th class=" flex items-center justify-center px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $t('microsites_table.logo') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $t('microsites_table.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(item, index) in data" :key="index" class="transition duration-300 ease-in-out hover:bg-gray-100">
                            <template v-for="(value, key) in item">
                                <template v-if="key !== 'logo' && key !== 'id' && key !== 'slug'">
                                    <td :key="key" class="px-6 py-3 whitespace text-sm text-gray-900">
                                        <span v-if="key === 'microsite_type'">
                                            {{ $t(`micrositeTypes.${value}`) }}
                                        </span>
                                        <span v-else>{{ value }}</span>
                                    </td>
                                </template>
                            </template>
                            <td class="flex items-center justify-center p-2">
                                <div class="flex items-center justify-center">
                                    <img v-if="item.logo" class="rounded-lg h-16 object-contain" :src="`/storage/${item.logo}`" alt="Logo">
                                    <PhotoIcon v-else class="w-16 h-16 text-gray-400 object-contain hover:text-gray-600"/>
                                </div>
                            </td>
                            <td>
                                <div class="text-gray-400 flex justify-center">
                                    <Link v-if="can('microsites.edit')" :href="route('microsites.edit', item.id)">
                                        <PencilIcon class="w-6 hover:text-gray-500"/>
                                    </Link>
                                    <Link v-if="can('microsites.show')" class="mx-1" :href="route('microsites.show', item.id)">
                                        <EyeIcon class="w-6 hover:text-gray-500"/>
                                    </Link>
                                    <Link v-if="can('microsites.destroy')"  :href="route('microsites.destroy', item.id)" method="delete" as="button">
                                        <TrashIcon class="w-6 hover:text-red-500"/>
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

    <div class="flex justify-center">
        <Paginator :paginator="paginator.links"/>
    </div>

</template>
