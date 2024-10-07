<script setup>
import { ref } from 'vue';
import Paginator from '@/Components/Paginator.vue';
import SpanForm from "@/Layouts/Atoms/SpanForm.vue";

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

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleString('es-ES', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
    });
}

const statusColors = {
    pending: 'yellow',
    processing: 'cyan',
    APPROVED: 'green',
    failed: 'red',
    completed_with_errors: 'orange'
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
                               {{ $t(`subscription.${header}`) }}
                                <span v-if="sortKey === header">
                                    {{ sortOrder === 'asc' ? '▲' : '▼' }}
                                </span>
                            </th>
                           <!--
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $t('microsites_table.actions') }}
                            </th>
                            -->
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">

                        <tr v-if="!props.data.length" class="text-center">
                            <td colspan="100%" class="px-6 py-3 text-sm text-gray-500">
                                {{ $t('no_information') }}
                            </td>
                        </tr>

                        <tr
                            v-for="(item, index) in props.data"
                            :key="index"
                            class="transition duration-300 ease-in-out hover:bg-gray-100"
                        >
                            <td
                                v-for="header in headers"
                                :key="header"
                                class="px-6 py-3 whitespace text-sm text-gray-900"
                            >

                                <template v-if="header === 'paid_at'">
                                    {{ formatDate(item[header]) }}
                                </template>

                                <div v-else-if="header === 'status'">
                                    <span-form class="capitalize" :color="statusColors[item[header]]">
                                        {{ $t(`payment_status.${item[header]}`) }}
                                    </span-form>
                                </div>

                                <span v-else>
                                    {{ item[header] }}
                                </span>

                            </td>
                            <td>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-end items-center mt-2">
        <p class="text-sm text-gray-600">{{ $t('total_records') }}: {{ paginator.total }}</p>
    </div>
    <div class="flex justify-center">
        <Paginator :paginator="paginator.links"/>
    </div>
</template>

