<script setup>
import {Head, Link, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import SpanForm from "@/Layouts/Atoms/SpanForm.vue";
import {route} from "ziggy-js";
import PaymentTable from "@/Layouts/Organisms/PaymentTable.vue";
import SubscriptionPaymentTable from "@/Layouts/Organisms/SubscriptionPaymentTable.vue";

defineProps({
    subscription: {
        type: Object,
        default: () => []
    },
    subscriptionPayments: {
        type: Object,
        default: () => []
    }
});

const statusColors = {
    PENDING: 'yellow',
    ACTIVE: 'green',
    CANCELLED: 'red',
    PAUSED: 'cyan',
    REJECTED: 'orange',
    EXPIRED: 'blue'
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

const headers = ["request_id","plan","amount","status","attempt_count","paid_at"];


</script>

<template>
        <Head :title="$t('subscription.detailer')"/>
        <AuthenticatedLayout>
                <div class="flex justify-between items-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('subscription.detailer') }}</h2>
                    <Link
                        :href="route('subscriptions.index')"
                        class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        {{ $t('subscription.list') }}
                    </Link>
                </div>

            <main>
                <div class="container mx-auto px-10 my-5 bg-white rounded-2xl shadow-2xl">
                    <div class="mt-6 border-t border-gray-100">
                        <dl class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-6">

                            <div v-if="subscription.reference !== null" class="px-4 py-3">
                                <dt class="text-sm font-medium leading-6 text-gray-400">Referencia de la subscripción:</dt>
                                <dd class="text-md leading-6 text-gray-700 font-semibold">{{ subscription.reference }}</dd>
                            </div>

                            <div v-if="subscription.description !== null" class="px-4 py-3">
                                <dt class="text-sm font-medium leading-6 text-gray-400">Descripción:</dt>
                                <dd class="text-md leading-6 text-gray-700 font-semibold">{{ subscription.description }}</dd>
                            </div>

                            <div v-if="subscription.name !== null" class="px-4 py-3">
                                <dt class="text-sm font-medium leading-6 text-gray-400">Nombre:</dt>
                                <dd class="text-md leading-6 text-gray-700 font-semibold">{{ subscription.name }} {{ subscription.surname }}</dd>
                            </div>

                            <div v-if="subscription.email !== null" class="px-4 py-3">
                                <dt class="text-sm font-medium leading-6 text-gray-400">Email:</dt>
                                <dd class="text-md leading-6 text-gray-700 font-semibold">{{ subscription.email }}</dd>
                            </div>

                            <div v-if="subscription.created_at !== null" class="px-4 py-3">
                                <dt class="text-sm font-medium leading-6 text-gray-400">Fecha de creación la subscripción:</dt>
                                <dd class="text-md leading-6 text-gray-700 font-semibold">{{ formatDate(subscription.created_at) }}</dd>
                            </div>

                            <div v-if="subscription.subscription_plan.name !== null" class="px-4 py-3">
                                <dt class="text-sm font-medium leading-6 text-gray-400">Plan de Suscripción:</dt>
                                <dd class="text-md leading-6 text-gray-700 font-semibold">{{ subscription.subscription_plan.name }}</dd>
                            </div>

                            <div v-if="subscription.subscription_plan.amount !== null" class="px-4 py-3">
                                <dt class="text-sm font-medium leading-6 text-gray-400">Detalle plan de Suscripción:</dt>
                                <dd class="text-md leading-6 text-gray-700 font-semibold">{{ subscription.subscription_plan.expiration_time }} x ${{ subscription.subscription_plan.amount }} {{ subscription.subscription_plan.currency }} / {{ subscription.subscription_plan.subscription_period }}</dd>
                            </div>


                            <div v-if="subscription.microsite.name !== null" class="px-4 py-3">
                                <dt class="text-sm font-medium leading-6 text-gray-400">Micrositio:</dt>
                                <dd class="text-md leading-6 text-gray-700 font-semibold">{{ subscription.microsite.name }}</dd>
                            </div>


                            <div v-if="subscription.status !== null "  class="px-4 py-3">
                                <dt class="text-sm font-medium leading-6 text-gray-400"> {{ $t('subscription.status') }} :</dt>
                                <dd class="text-md leading-6 text-gray-700 font-semibold">
                                    <span-form :color="statusColors[subscription.status]">{{ $t(`payment_status.${subscription.status}`) }}</span-form>
                                </dd>
                            </div>

                        </dl>
                        <dl class="flex justify-center">
                            <div v-if="can('subscriptions.destroy') && subscription.status === 'APPROVED' "  class="px-4 py-3">
                                <Link
                                    :href="route('subscriptions.destroy', subscription.id)"
                                    class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    method="delete"
                                    as="button"
                                >
                                    {{$t('subscription.cancelSubscription')}}
                                </Link>
                            </div>
                        </dl>
                    </div>
                </div>
            </main>
            <main v-if="subscriptionPayments.data.length > 0">
                <SubscriptionPaymentTable :data="subscriptionPayments.data" :paginator="subscriptionPayments" :headers="headers" />
            </main>
        </AuthenticatedLayout>
</template>

<style scoped>

</style>
