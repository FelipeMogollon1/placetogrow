<template>
    <Head :title="$t('dashboard')" />
    <AuthenticatedLayout>
        <div class="p-6 bg-white shadow-md rounded">

            <form @submit.prevent="filterInvoices" class="flex flex-col items-center justify-center mb-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input
                        type="date"
                        v-model="startDate"
                        class="p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                    />
                    <input
                        type="date"
                        v-model="endDate"
                        class="p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-orange-500"
                    />
                    <button
                        type="submit"
                        class="bg-orange-500 text-white p-2 rounded hover:bg-orange-600 transition duration-200"
                    >
                        {{ $t('filter') }}
                    </button>
                </div>
                <div v-if="errorMessage" class="mt-2 text-red-500 text-center">{{ errorMessage }}</div>

            </form>

            <div class="flex flex-col items-center justify-center">
                <div class="bg-gray-50 p-4 border border-1 rounded-lg w-full max-w-3xl ">
                    <h2 class="font-semibold mb-2">{{ $t('invoice_summary') }}</h2> <!-- Traducción -->
                    <canvas ref="salesChart"></canvas>
                </div>
            </div>

            <div class="flex items-center justify-between mt-4">
                <div class="bg-gray-50 p-4 border border-1 rounded-lg w-full max-w-md">
                    <h2 class="font-semibold mb-2">{{ $t('pending_vs_overdue') }}</h2> <!-- Traducción -->
                    <canvas ref="pendingOverdueChart"></canvas>
                </div>
                <div class="bg-gray-50 p-4 border border-1 rounded-lg w-full max-w-md">
                    <h2 class="font-semibold mb-2">{{ $t('paid_vs_pending') }}</h2> <!-- Traducción -->
                    <canvas ref="paidPendingChart"></canvas>
                </div>
            </div>

            <div class="bg-gray-100 p-6 rounded-lg mt-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-green-100 border-l-4 border-green-400 p-4 rounded-lg shadow-lg flex items-center justify-between">
                        <div class="text-2xl font-semibold text-gray-700">{{ metrics.totalPaid }}</div>
                        <div class="text-sm font-medium text-green-600">{{ $t('invoices_paid') }}</div>
                    </div>

                    <div class="bg-yellow-100 border-l-4 border-yellow-400 p-4 rounded-lg shadow-lg flex items-center justify-between">
                        <div class="text-2xl font-semibold text-gray-700">{{ metrics.totalPending }}</div>
                        <div class="text-sm font-medium text-yellow-600">{{ $t('invoices_pending') }}</div>
                    </div>

                    <div class="bg-red-100 border-l-4 border-red-400 p-4 rounded-lg shadow-lg flex items-center justify-between">
                        <div class="text-2xl font-semibold text-gray-700">{{ metrics.totalOverdue }}</div>
                        <div class="text-sm font-medium text-red-600">{{ $t('invoices_overdue') }}</div>
                    </div>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from "vue";
import { Head, usePage } from '@inertiajs/vue3';
import Chart from "chart.js/auto";
import { Inertia } from "@inertiajs/inertia";
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const page = usePage();
const props = ref(page.props);



const metrics = ref({
    totalPaid: props.value.totalPaid,
    totalPending: props.value.totalPending,
    totalOverdue: props.value.totalOverdue,
});

const startDate = ref(props.value.startDate);
const endDate = ref(props.value.endDate);
const errorMessage = ref('');

const salesChart = ref(null);
const paidPendingChart = ref(null);
const pendingOverdueChart = ref(null);

const filterInvoices = async () => {
    if (!startDate.value || !endDate.value) {
        errorMessage.value = t("error.select_dates");
        return;
    }
    if (new Date(startDate.value) > new Date(endDate.value)) {
        errorMessage.value = t("error.start_date_before_end");
        return;
    }

    errorMessage.value = '';
    await Inertia.get('/dashboard', { start_date: startDate.value, end_date: endDate.value });
};

onMounted(() => {
    const ctxSales = salesChart.value.getContext('2d');
    new Chart(ctxSales, {
        type: 'bar',
        data: {
            labels: [t('invoices.paid'), t('invoices.pending'), t('invoices.overdue')],
            datasets: [{
                label: t('invoices.label'),
                data: [metrics.value.totalPaid, metrics.value.totalPending, metrics.value.totalOverdue],
                backgroundColor: [
                    'rgba(104,216,104,0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(255, 99, 132, 0.6)'
                ],
                borderColor: [
                    'rgba(104,216,104, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1,
                barPercentage: 0.5,
                categoryPercentage: 0.8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            animation: {
                duration: 1500,
                easing: 'easeInOutQuad'
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const value = context.raw || 0;
                            return `${label}: ${value}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: t('invoices.quantity')
                    }
                }
            }
        }
    });


    const ctxPaidPending = paidPendingChart.value.getContext('2d');
    new Chart(ctxPaidPending, {
        type: 'doughnut',
        data: {
            labels: [t('invoices.paid'), t('invoices.pending')],
            datasets: [{
                label: t('invoices.label'),
                data: [metrics.value.totalPaid, metrics.value.totalPending],
                backgroundColor: [
                    'rgba(104,216,104, 0.3)',
                    'rgba(255, 206, 86, 0.3)',
                ],
                borderColor: [
                    'rgba(104,216,104, 1)',
                    'rgba(255, 206, 86, 1)',
                ],
                borderWidth: 2,
                hoverOffset: 10,
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxHeight: 10,
                        padding: 20,
                    },
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                },
            },
            cutout: '60%',
        }
    });


    const ctxPendingOverdue = pendingOverdueChart.value.getContext('2d');
    new Chart(ctxPendingOverdue, {
        type: 'pie',
        data: {
            labels: [t('invoices.pending'), t('invoices.overdue')],
            datasets: [{
                label: t('invoices.label'), // Traducción
                data: [metrics.value.totalPending, metrics.value.totalOverdue],
                backgroundColor: [
                    'rgba(255, 206, 86, 0.3)',
                    'rgba(255, 99, 132, 0.3)'
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)'

                ],
                borderWidth: 2,
                hoverOffset: 10,
            }]
        },
        options: {
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        boxHeight: 10,
                        padding: 20,
                    },
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                },
            },
            scale: {
                ticks: {
                    beginAtZero: true,
                    stepSize: 10,
                    color: '#666',
                },
                grid: {
                    color: 'rgba(200, 200, 200, 0.5)',
                },
            },
        }
    });
});
</script>
