<script setup>
import {Head, Link, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {ref} from "vue";
import SpanForm from "@/Layouts/Atoms/SpanForm.vue";

const page = usePage();
const payment = ref(page.props.payment || {});
const microsite = ref(page.props.payment.microsite || {});

const statusColors = {
    PENDING: 'yellow',
    APPROVED: 'green',
    REJECTED: 'red',
    APPROVED_PARTIAL: 'cyan',
    PARTIAL_EXPIRED: 'orange',
    UNKNOWN: 'blue'
};

</script>

<template>
        <Head :title="$t('detail_microsite')"/>
        <AuthenticatedLayout>
                <div class="flex justify-between items-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('payment.payment_detailer') }}</h2>
                    <Link
                        :href="route('payments.index')"
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

                                <div v-if="payment.reference !== null " class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.reference') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ payment.reference }}</dd>
                                </div>

                                <div v-if="payment.receipt !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.receipt') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ payment.receipt }}</dd>
                                </div>

                                <div v-if="payment.payer_name !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.payer_name') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ payment.payer_name }}</dd>
                                </div>

                                <div v-if="payment.payer_surname !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.payer_surname') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ payment.payer_surname }}</dd>
                                </div>

                                <div v-if="payment.payer_email !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.payer_email') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ payment.payer_email }}</dd>
                                </div>

                                <div v-if="payment.payer_phone !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.payer_phone') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ payment.payer_phone }}</dd>
                                </div>

                                <div v-if="payment.payer_company !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.payer_company') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ payment.payer_company }}</dd>
                                </div>

                                <div v-if="payment.payer_document_type !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.payer_document_type') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ payment.payer_document_type }}</dd>
                                </div>

                                <div v-if="payment.payer_document !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.payer_document') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ payment.payer_document }}</dd>
                                </div>

                                <div v-if="payment.description !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.description') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ payment.description }}</dd>
                                </div>

                                <div v-if="payment.amount !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.amount') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ payment.amount }}</dd>
                                </div>

                                <div v-if="payment.paid_at !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.paid_at') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ payment.paid_at }}</dd>
                                </div>

                                <div v-if="payment.currency !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.currency') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">{{ payment.currency }}</dd>
                                </div>

                                <div v-if="payment.status !== null "  class="px-4 py-3">
                                    <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('payment.status') }} :</dt>
                                    <dd class="text-md leading-6 text-gray-700 font-semibold">
                                        <span-form :color="statusColors[payment.status]">{{ $t(`payment_status.${payment.status}`) }}</span-form>
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
