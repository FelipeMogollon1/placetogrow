<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { EyeIcon, CreditCardIcon } from '@heroicons/vue/24/outline';
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

const statusColors = {
    PENDING: 'yellow',
    APPROVED: 'green',
    REJECTED: 'red',
    APPROVED_PARTIAL: 'cyan',
    PARTIAL_EXPIRED: 'orange',
    UNKNOWN: 'blue'
};

const typeMicrosite = {
    donation: 'violet',
    invoice: 'orange',
    subscription: 'cyan'
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
                               {{ $t(`invoices.${header}`) }}
                                <span v-if="sortKey === header">
                                    {{ sortOrder === 'asc' ? '▲' : '▼' }}
                                </span>
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ $t('microsites_table.actions') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">

                        <tr v-if="!props.data.length" class="text-center">
                            <td colspan="100%" class="px-6 py-3 text-sm text-gray-500">
                                {{ $t('no_information') }}<!-- Mensaje de no información -->
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
                                 <template v-if="header === 'microsite_type'">
                                    <span-form class="capitalize" :color="typeMicrosite[item[header]]">
                                            {{ $t(`micrositeTypes.${item[header]}`) }}
                                    </span-form>
                                 </template>

                                <template v-else-if="header === 'amount'">
                                    <span v-if="item['currency'] === 'USD'">${{ item[header].toLocaleString('en-US') }}</span>
                                    <span v-else>${{ item[header].toLocaleString('es-CO') }}</span>
                                </template>

                                <template v-else-if="header === 'status'">
                                    <span-form class="capitalize" :color="statusColors[item[header]]">
                                        {{ $t(`payment_status.${item[header]}`) }}
                                    </span-form>
                                </template>

                                <span v-else>
                                    {{ item[header] }}
                                </span>

                            </td>
                            <td>
                                <div class="text-gray-400 flex justify-center">
                                    <Link v-if="can('invoices.show')" class="mx-1" :href="route('invoices.show', item.id)">
                                        <EyeIcon class="w-6 hover:text-gray-500"/>
                                    </Link>

                                    <Link v-if="can('invoices.store') && item.status !== 'APPROVED'" class="mx-1" :href="route('invoices.create', item.id)">
                                        <CreditCardIcon title="Realizar el pago" class="w-6 hover:text-gray-500"/>
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

    <div class="flex justify-center mt-4">
        <Paginator :paginator="paginator.links"/>
    </div>
</template>

