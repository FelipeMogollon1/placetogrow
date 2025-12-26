<script setup>
import { ref } from 'vue';
import { Head, Link, useForm, usePage } from "@inertiajs/vue3";
import { useI18n } from 'vue-i18n';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { route } from "ziggy-js";
import { CheckBadgeIcon } from "@heroicons/vue/24/outline/index.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const { t } = useI18n();
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
const showModal = ref(false);
const warningMessage = ref('');

const selectPlan = (id) => {
    if (String(id) === String(selectedPlan.value)) {
        warningMessage.value = t("subscription.currentPlanWarning");
    } else {
        warningMessage.value = '';
    }
    selectedPlan.value = id;
};

const confirmUpdate = () => {
    if (String(selectedPlan.value) === String(page.props.subscription[0].subscription_plan_id)) {
        warningMessage.value = t("subscription.currentPlanWarning");
    } else {
        form.subscription_plan_id = selectedPlan.value;
        showModal.value = true;
        warningMessage.value = '';
    }
};

function updateSubscription() {
    form.put(route('subscriptions.update', page.props.subscription[0].id));
    showModal.value = false;
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
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $t('subscription.editSubscription') }}
                </h2>
                <Link
                    :href="route('subscriptions.index')"
                    class="inline-flex items-center px-4 py-2 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition duration-150 ease-in-out"
                >
                    {{ $t('subscription.subscriptions') }}
                </Link>
            </div>
        </template>

        <main class="flex flex-col md:flex-row justify-center">
            <section class="w-full md:w-3/5 bg-white grid grid-cols-1 sm:grid-cols-1 p-7 m-7 rounded-2xl shadow-lg">
                <h2 class="text-3xl font-bold mb-4 text-gray-800">
                    {{ $t('subscription.changePlan') }}
                </h2>

                <div class="space-y-4">
                    <div
                        v-for="plan in subscriptionPlans"
                        :key="plan.id"
                        :class="[ 'p-6 border rounded-md flex items-center justify-between transition-transform duration-300 cursor-pointer hover:scale-105', plan.id === selectedPlan ? 'border-orange-600 bg-orange-50 shadow-lg' : 'border-gray-300 hover:bg-gray-50' ]"
                        @click="selectPlan(plan.id)"
                    >
                        <div>
                            <p class="font-bold text-xl capitalize text-gray-600">
                                {{ plan.name }}
                            </p>
                            <div class="space-y-1 mt-2">
                                <p
                                    v-for="(feature, i) in JSON.parse(plan.description)"
                                    :key="i"
                                    class="flex items-center text-gray-600 text-sm"
                                >
                                    <CheckBadgeIcon class="w-4 h-4 mr-1 text-orange-500" />
                                    <span>{{ feature }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="text-right">
                            <p class="text-lg font-bold text-gray-900">
                                {{ plan.expiration_time }} x ${{ (1 * plan.amount).toLocaleString(plan.currency === 'USD' ? 'en-US' : 'es-CO') }}
                                {{ plan.currency }} /
                                <span class="text-sm">{{ $t(`subscription.${plan.subscription_period}`) }}</span>
                            </p>
                            <p class="text-lg font-bold text-orange-700 mt-1">
                                {{ $t('subscription.totalPrice') }} ${{ (plan.expiration_time * plan.amount).toLocaleString(plan.currency === 'USD' ? 'en-US' : 'es-CO') }} {{ plan.currency }}
                            </p>
                        </div>
                    </div>

                    <p v-if="warningMessage" class="text-red-500 text-sm mt-2">{{ warningMessage }}</p>
                </div>

                <div class="mt-6 flex justify-end">
                    <PrimaryButton @click="confirmUpdate()">
                        {{ $t('subscription.continue') }}
                    </PrimaryButton>
                </div>
            </section>

            <section class="w-full md:w-2/5 mt-4">
                <div class="bg-white rounded-2xl shadow-lg p-4 m-4">
                    <h2 class="text-2xl font-bold mb-4 text-gray-800">
                        {{ $t('subscription.paymentMethod') }}
                    </h2>

                    <div v-for="(sub, index) in subscription"
                         :key="index" class="w-[300px] h-[150px] mx-auto perspective-1000"
                         @click="rotateCard"
                    >
                        <div :class="{'rotate-y-180': isFlipped}" class="relative w-full h-full transition-transform duration-700 transform-style-preserve-3d">
                            <div class="absolute w-full h-full bg-gradient-to-r from-orange-600 to-orange-300 text-white p-4 rounded-md shadow-lg backface-hidden">
                                <div class="flex justify-between items-center mb-2">
                                    <p class="text-lg font-semibold">{{ $t('subscription.creditCard') }}</p>
                                    <img
                                        v-if="sub.franchiseName === 'Visa'"
                                        src="https://cdn.visa.com/v2/assets/images/logos/visa/blue/logo.png"
                                        alt="Visa"
                                        class="w-10">
                                    <img
                                        v-else-if="sub.franchiseName === 'MasterCard'"
                                        src="https://mtf.mastercard.co.za/content/dam/public/mastercardcom/mea/za/logos/mc-logo-52.svg"
                                        alt="Master Card"
                                        class="w-10">
                                    <p v-else>{{ sub.franchiseName }}</p>
                                </div>
                                <div class="mt-6">
                                    <p class="text-md">•••• •••• •••• {{ sub.lastDigits }}</p>
                                    <div class="flex justify-between mt-2 text-xs">
                                        <p>{{ sub.name }} {{ sub.surname }}</p>
                                        <p>{{ formatDate(sub.validUntil) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="absolute w-full h-full bg-gray-700 text-white p-4 rounded-md shadow-lg transform rotate-y-180 backface-hidden">
                                <div class="w-full bg-black h-8 mb-2"></div>
                                <div class="flex justify-between items-center text-xs">
                                    <p>CVV</p>
                                    <p class="text-md bg-white text-black p-1 rounded-md">123</p>
                                </div>
                                <p class="text-xs text-gray-300 mt-2">{{ $t(`payment_status.${sub.status}`) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded-lg p-6 max-w-sm mx-auto">
                <h3 class="text-lg font-bold mb-4">{{ $t('subscription.confirmChange') }}</h3>
                <p>{{ $t('subscription.confirmChangeMessage') }}</p>
                <p class="pt-2 text-gray-900 font-bold">{{ $t('subscription.additional') }}</p>
                <div class="mt-4 flex justify-end">
                    <PrimaryButton @click="updateSubscription()">{{ $t('subscription.acceptAndContinue') }}</PrimaryButton>
                    <button @click="showModal = false" class="ml-2 text-gray-500">{{ $t('subscription.cancel') }}</button>
                </div>
            </div>
        </div>
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

.fixed {
    position: fixed;
}
.bg-black {
    background-color: rgba(0, 0, 0, 0.5);
}
</style>

