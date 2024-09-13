<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { route } from "ziggy-js";
import { CheckBadgeIcon } from "@heroicons/vue/24/outline/index.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const page = usePage();

defineProps({
    subscriptionPlans: {
        type: Array,
        default: () => []
    },
    subscription: {
        type: Array,
        default: () => []
    }
});

const form = useForm({
    subscription_plan_id: '',
    reference: page.props.subscription[0].reference,
});

const selectedPlan = ref(page.props.subscription[0].subscription_plan_id);

const selectPlan = (id) => {
    selectedPlan.value = id;
};

function updateSubscription() {
    form.subscription_plan_id = selectedPlan.value;
    form.put(route('subscriptions.update', page.props.subscription[0].id));
}

const isFlipped = ref(false);

const rotateCard = () => {
    isFlipped.value = !isFlipped.value;
};

const formatDate = (dateStr) => {
    const date = new Date(dateStr);
    const month = date.getMonth() + 1;
    const year = date.getFullYear().toString().slice(-2);
    return `${month.toString().padStart(2, '0')}/${year}`;
};
</script>

<template>
    <Head :title="$t('subscription.editSubscription')" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                    {{ $t('subscription.editSubscription') }}
                </h2>
                <Link
                    :href="route('subscriptions.index')"
                    class="inline-flex items-center px-5 py-3 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150 ease-in-out"
                >
                    {{ $t('subscription.subscriptions') }}
                </Link>
            </div>
        </template>

        <main class="max-w-4xl mx-auto mt-10 p-8 bg-white shadow-2xl rounded-xl space-y-10">
            <!-- Sección de la tarjeta de crédito -->
            <section>
                <h2 class="text-4xl font-extrabold text-center text-gray-800 mb-6">
                    {{ $t('subscription.paymentMethod') }} 💳
                </h2>

                <div v-for="(sub, index) in subscription" :key="index" class="w-[350px] h-[200px] mx-auto perspective-1000" @click="rotateCard">
                    <div :class="{'rotate-y-180': isFlipped}" class="relative w-full h-full transition-transform duration-700 transform-style-preserve-3d">

                        <div class="absolute w-full h-full bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-6 rounded-lg shadow-xl backface-hidden">
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-xl font-semibold">Credit Card</p>
                                <img
                                    v-if="sub.franchiseName === 'Visa'"
                                    src="https://cdn.visa.com/v2/assets/images/logos/visa/blue/logo.png"
                                    alt="Visa"
                                    class="w-12">
                                <img
                                    v-else-if="sub.franchiseName === 'MasterCard'"
                                    src="https://mtf.mastercard.co.za/content/dam/public/mastercardcom/mea/za/logos/mc-logo-52.svg"
                                    alt="Master Card"
                                    class="w-12">
                                <p v-else>{{ sub.franchiseName }}</p>
                            </div>
                            <div class="mt-10">
                                <p class="text-lg">•••• •••• •••• {{ sub.lastDigits }}</p>
                                <div class="flex justify-between mt-4 text-sm">
                                    <p>{{ sub.name }} {{ sub.surname }}</p>
                                    <p>{{ formatDate(sub.validUntil) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Cara trasera de la tarjeta -->
                        <div class="absolute w-full h-full bg-gray-800 text-white p-6 rounded-lg shadow-xl transform rotate-y-180 backface-hidden">
                            <div class="w-full bg-black h-10 mb-4"></div>
                            <div class="flex justify-between items-center text-sm">
                                <p>CVV</p>
                                <p class="text-lg bg-white text-black p-2 rounded-md">123</p>
                            </div>
                            <p class="text-sm text-gray-300 mt-4">{{ $t(`payment_status.${sub.status}`) }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Sección de cambio de plan -->
            <section>
                <h2 class="text-4xl font-bold mb-6 text-gray-800">{{ $t('subscription.changePlan') }}</h2>

                <div class="space-y-6">
                    <div
                        v-for="plan in subscriptionPlans"
                        :key="plan.id"
                        :class="[
                            'p-8 border rounded-xl flex items-center justify-between transition-transform duration-300 cursor-pointer hover:scale-105',
                            plan.id === selectedPlan ? 'border-blue-600 bg-blue-50 shadow-lg' : 'border-gray-300 hover:bg-gray-50'
                        ]"
                        @click="selectPlan(plan.id)"
                    >
                        <div>
                            <p class="font-extrabold text-2xl capitalize text-blue-700">
                                {{ plan.name }}
                            </p>
                            <div class="space-y-2 mt-4">
                                <p
                                    v-for="(feature, i) in JSON.parse(plan.description)"
                                    :key="i"
                                    class="flex items-center text-gray-700 text-sm"
                                >
                                    <CheckBadgeIcon class="w-5 h-5 mr-2 text-green-500" />
                                    <span>{{ feature }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="text-xl font-bold text-gray-900">
                                {{ plan.expiration_time }} x ${{ (1 * plan.amount).toLocaleString(plan.currency === 'USD' ? 'en-US' : 'es-CO') }} {{ plan.currency }} / {{ $t(`subscription.${plan.subscription_period}`) }}
                            </p>
                            <p class="text-xl font-bold text-blue-700 mt-2">
                                {{ $t('subscription.totalPrice') }} ${{ (plan.expiration_time * plan.amount).toLocaleString(plan.currency === 'USD' ? 'en-US' : 'es-CO') }} {{ plan.currency }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <PrimaryButton @click="updateSubscription()">
                        {{ $t('subscription.continue') }}
                    </PrimaryButton>
                </div>
            </section>
        </main>
    </AuthenticatedLayout>
</template>

<style scoped>
.perspective-1000 {
    perspective: 1000px;
}

.backface-hidden {
    backface-visibility: hidden;
}

.rotate-y-180 {
    transform: rotateY(180deg);
}

.transform-style-preserve-3d {
    transform-style: preserve-3d;
}
</style>
