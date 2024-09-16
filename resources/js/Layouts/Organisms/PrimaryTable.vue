<script setup>
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { TrashIcon, PencilIcon, EyeIcon } from '@heroicons/vue/24/outline';
import Paginator from '@/Components/Paginator.vue';
import {ref} from "vue";

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

const sortKey = ref('');
const sortOrder = ref('asc');

const sortData = (key) => {
    sortOrder.value = (sortKey.value === key && sortOrder.value === 'asc') ? 'desc' : 'asc';
    sortKey.value = key;

    props.data.sort((a, b) => {
        if (a[key] < b[key]) return sortOrder.value === 'asc' ? -1 : 1;
        if (a[key] > b[key]) return sortOrder.value === 'asc' ? 1 : -1;
        return 0;
    });
};

</script>

<template>
    <div class="flex flex-col mt-8">
        <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden rounded-2xl border border-1 shadow">
                    <table class="min-w-full">
                        <thead class="bg-gray-200 border-b">
                        <tr>
                            <th
                                v-for="header in headers"
                                :key="header"
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                @click="sortData(header)"
                                :class="{ 'cursor-pointer': true, 'bg-gray-300': sortKey === header }"
                            >
                                {{ $t(`category.${header}`) }}
                                <span v-if="sortKey === header">
                                    {{ sortOrder === 'asc' ? '▲' : '▼' }}
                                </span>
                            </th>

                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $t('microsites_table.actions') }}</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="(item, index) in data" :key="index" class="transition duration-300 ease-in-out hover:bg-gray-100">
                            <template v-for="(value, key) in item">
                                <template v-if="key !== 'logo' && key !== 'id' && key !== 'slug'">
                                    <td :key="key" class="px-6 py-3 whitespace text-sm text-gray-900">
                                        <span>{{ value }}</span>
                                    </td>
                                </template>
                            </template>

                            <td>
                                <div class="text-gray-400 flex justify-center">
                                    <Link
                                        v-if="can('categories.edit')"
                                        class="m-1"
                                        :href="route('categories.edit',item.id)"
                                    >
                                       <span class="material-symbols-outlined">
                                            <PencilIcon class="w-6 hover:text-gray-500"/>
                                        </span>
                                    </Link>
                                    <Link
                                        class="m-1"
                                        v-if="can('categories.destroy')"
                                        :href="route('categories.destroy',item.id)"
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

    <div class="flex justify-center">
        <Paginator :paginator="paginator.links"/>
    </div>

</template>
