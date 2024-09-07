<template>
    <Head :title="$t('subscription.newSubscriptionPlan')" />
    <div class="bg-white overflow-hidden border border-gray-100 sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form class="grid grid-cols-1 gap-6 sm:grid-cols-2" @submit.prevent="submit">

                <div class="md:col-span-2">
                    <InputLabel for="name" :value="$t('subscription.name')" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        :placeholder="$t('subscription.name')"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="md:col-span-2">
                    <InputLabel for="description" :value="$t('subscription.description')" />
                    <div v-for="(desc, index) in form.description" :key="index" class="flex space-x-2 items-center mt-1">
                        <TextInput
                            type="text"
                            class="block w-full"
                            v-model="form.description[index]"
                            :placeholder="$t('subscription.description')"
                        />
                        <button type="button" @click="removeDescription(index)" class="text-red-500 hover:text-red-700">
                            &times;
                        </button>
                    </div>
                    <button
                        type="button"
                        @click="addDescription"
                        class="mt-2 text-sm text-orange-600 hover:underline"
                    >
                        + {{ $t('subscription.addDescription') }}
                    </button>
                    <InputError class="mt-2" :message="form.errors.description" />
                </div>

                <div>
                    <InputLabel for="subscription_period" :value="$t('subscription.subscription_period')" />
                    <select
                        id="subscription_period"
                        v-model="form.subscription_period"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-gray-500 focus:border-gray-500 sm:text-sm"
                        required
                    >
                        <option v-for="(period, index) in periods" :key="index" :value="period">
                            {{ $t(`subscription.${period}`) }}
                        </option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.subscription_period" />
                </div>

                <div>
                    <InputLabel for="expiration_time" :value="$t('subscription.expiration_time')" />
                    <TextInput
                        id="expiration_time"
                        type="number"
                        class="mt-1 block w-full"
                        v-model.number="form.expiration_time"
                        min="1"
                        :placeholder="$t('subscription.expiration_time')"
                    />
                    <InputError class="mt-2" :message="form.errors.expiration_time" />
                </div>

                <div v-if="microsite.currency === 'BOTH'">
                    <InputLabel for="currency" :value="$t('subscription.currency')" />
                    <select
                        id="currency"
                        v-model="form.currency"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-gray-500 focus:border-gray-500 sm:text-sm"
                    >
                        <option v-for="(currency, index) in filteredCurrency" :key="index" :value="currency">
                            {{ $t(`currencies.${currency}`) }}
                        </option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.currency" />
                </div>

                <div>
                    <InputLabel for="amount" :value="$t('subscription.amount')" />
                    <TextInput
                        id="amount"
                        type="number"
                        class="mt-1 block w-full"
                        v-model.number="form.amount"
                        step="0.01"
                        :placeholder="$t('subscription.amount')"
                    />
                    <InputError class="mt-2" :message="form.errors.amount" />
                </div>

                <div class="md:col-span-2">
                    <InputLabel for="additional_info" :value="$t('subscription.additional_info')" />
                    <TextInput
                        id="additional_info"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.additional_info"
                        :placeholder="$t('subscription.additional_info')"
                    />
                    <InputError class="mt-2" :message="form.errors.additional_info" />
                </div>

                <div class="md:col-span-2">
                    <InputLabel for="expiration_additional_info" :value="$t('subscription.expiration_additional_info')" />
                    <TextInput
                        id="expiration_additional_info"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.expiration_additional_info"
                        :placeholder="$t('subscription.expiration_additional_info')"
                    />
                    <InputError class="mt-2" :message="form.errors.expiration_additional_info" />
                </div>

                <div class="md:col-span-2 flex justify-center">
                    <PrimaryButton @click="submit">
                        {{ $t('subscription.createSubscription') }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { route } from "ziggy-js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const emit = defineEmits(['closeModal']);

const props = defineProps({
    periods: {
        type: Object,
        default: () => []
    },
    microsite:{
        type: Object,
        default: () => []
    },
    currency:{
        type: Object,
        default: () => []
    }
});

const initialValues = {
    name: "",
    description: [""],
    amount: 0,
    currency: props.microsite.currency !== "BOTH" ? props.microsite.currency : "COP",
    subscription_period: "monthly",
    expiration_time: 1,
    microsite_id: props.microsite.id ?? "",
    additional_info: "",
    expiration_additional_info: ""
};

const form = useForm(initialValues);

const submit = () => {
    form.post(route('subscriptionsPlan.store'), {
        onSuccess: () => {
            emit('closeModal');
        }
    });
};

const addDescription = () => {
    form.description.push('');
};

const removeDescription = (index) => {
    form.description.splice(index, 1);
};

const filteredCurrency = props.currency.filter(currency => currency !== 'BOTH');

</script>
