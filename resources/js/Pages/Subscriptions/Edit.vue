<script setup>
import { ref } from 'vue';
import {Head, Link, useForm, usePage} from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { route } from "ziggy-js";
import {CheckBadgeIcon,CheckIcon} from "@heroicons/vue/24/outline/index.js";

defineProps({
    subscriptionPlans: {
        type: Array,
        default: () => []
    }
});

const form = useForm({
    subscription_plan_id: ''
});

const selectedPlan = ref(usePage().props.subscriptionPlan[0].id); // Ahora seleccionamos el ID inicial correctamente.

const selectPlan = (id) => {
    selectedPlan.value = id; // Cambiamos el valor de selectedPlan al hacer clic en el plan.
};

function updateSubscription() {
    form.subscription_plan_id = selectedPlan.value; // Aseguramos que el plan seleccionado sea parte del form.
    form.put(route('subscriptions.update', form.id));
}
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
        <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-6">
            <h2 class="text-3xl font-semibold mb-8 text-gray-800">Cambiar de plan</h2>

            <div class="space-y-6">
                <div
                    v-for="plan in subscriptionPlans"
                    :key="plan.id"
                    :class="[
                        'p-6 border rounded-lg flex items-center justify-between transition-all duration-200 cursor-pointer',
                        plan.id === selectedPlan ? 'border-blue-500 bg-gray-100 shadow-lg' : 'border-gray-200 hover:bg-gray-50'
                    ]"
                    @click="selectPlan(plan.id)"
                >
                    <div>
                        <p class="font-extrabold text-xl ">
                            {{ plan.name }}
                        </p>
                        <div class="space-y-2 mt-2">
                            <p
                                v-for="(feature, i) in JSON.parse(plan.description)"
                                :key="i"
                                class="flex items-center text-gray-700 lg:text-md md:text-sm"
                            >
                                <CheckBadgeIcon class="w-6 h-6 mr-2 text-green-500" />
                                <span class="">{{ feature }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900">{{plan.expiration_time}} x {{ plan.amount }} {{ plan.currency }} / {{ plan.subscription_period }}</p>
                    </div>
                </div>
            </div>

            <p class="text-sm text-gray-500 mt-6">
                La disponibilidad del contenido en HD (720p), Full HD (1080p), Ultra HD (4K) y HDR depende de tu servicio de internet y del dispositivo en uso. Consulta nuestros
                <a href="#" class="text-blue-500 underline">Términos de uso</a> para obtener más información.
            </p>
        </div>
    </AuthenticatedLayout>
</template>
