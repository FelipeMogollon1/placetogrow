<script setup>
import {Head, Link, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {ref} from "vue";
import SpanForm from "@/Layouts/Atoms/SpanForm.vue";

const page = usePage();
const invoice = ref(page.props.invoice || {});
const microsite = ref(page.props.invoice.microsite || {});

const statusColors = {
    PENDING: 'yellow',
    APPROVED: 'green',
    REJECTED: 'red',
    APPROVED_PARTIAL: 'cyan',
    PARTIAL_EXPIRED: 'orange',
    UNKNOWN: 'blue'
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: 'numeric',
        minute: 'numeric',
        second: 'numeric',
        timeZoneName: 'short'
    });
};

</script>

<template>
        <Head :title="$t('payment.payment_detailer')"/>
        <AuthenticatedLayout>
                <div class="flex justify-between items-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('payment.payment_detailer') }}</h2>
                    <Link
                        :href="route('invoices.index')"
                        class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        {{ $t('payment.payment_list') }}
                    </Link>
                </div>

                <main>
                    <div class="container mx-auto px-10 my-5 bg-white rounded-2xl shadow-2xl">
                        <div class="mt-6 border-t border-gray-100">
                            <dl class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6" >

                                <div v-if="microsite.name !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.microsite_name') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ microsite.name }}</dd>
                                </div>

                                <div v-if="invoice.reference !== null " class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.reference') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ invoice.reference }}</dd>
                                </div>

                                <div v-if="invoice.receipt !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.receipt') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ invoice.receipt }}</dd>
                                </div>

                                <div v-if="invoice.name !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.payer_name') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ invoice.name }}</dd>
                                </div>

                                <div v-if="invoice.surname !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.payer_surname') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ invoice.surname }}</dd>
                                </div>

                                <div v-if="invoice.email !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.payer_email') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ invoice.email }}</dd>
                                </div>

                                <div v-if="invoice.document_type !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.payer_document_type') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ $t(`documentType.${invoice.document_type }`) }}</dd>
                                </div>

                                <div v-if="invoice.document !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.payer_document') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ invoice.document }}</dd>
                                </div>

                                <div v-if="invoice.description !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.description') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ invoice.description }}</dd>
                                </div>

                                <div v-if="invoice.paid_at !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.paid_at') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ formatDate(invoice.paid_at) }} </dd>
                                </div>

                                <div class="px-4 py-3">
                                    <dt class="text-md font-medium leading-6 text-gray-400">{{ $t('voucher.InvoiceNumber') }}</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ invoice.request_id }}</dd>
                                </div>

                                <div v-if="invoice.currency_type !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.currency') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ $t(`currencies.${invoice.currency_type }`) }}</dd>
                                </div>

                                <div v-if="invoice.amount !== null " class="px-4 py-3">
                                    <dt class="text-md font-medium leading-6 text-gray-400">{{ $t('voucher.paid') }}</dt>
                                    <dd v-if="invoice.currency === 'USD' " class="text-md leading-6 text-gray-700 font-semibold">$ {{ invoice.amount.toLocaleString('en-US') }}</dd>
                                    <dd v-else class="text-md leading-6 text-gray-700 font-semibold">$ {{ invoice.amount.toLocaleString('es-CO') }}</dd>
                                </div>

                                <div v-if="invoice.created_at !== null " class="px-4 py-3">
                                    <dt class="text-md font-medium leading-6 text-gray-400">{{ $t('voucher.date') }}</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ formatDate(invoice.created_at) }}</dd>
                                </div>

                                <div v-if="invoice.status !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.status') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">
                                        <span-form :color="statusColors[invoice.status]">{{ $t(`payment_status.${invoice.status}`) }}</span-form>
                                    </dd>
                                </div>



                            </dl>
                        </div>
                    </div>
                </main>

        </AuthenticatedLayout>
</template>

<style scoped>

</style>
