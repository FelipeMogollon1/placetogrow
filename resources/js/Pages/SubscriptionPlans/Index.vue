<script setup>
import { ref, } from "vue";
import Modal from "@/Components/Modal.vue";
import Create from "@/Pages/SubscriptionPlans/Create.vue";
import SubscriptionsPlansTable from "@/Layouts/Organisms/SubscriptionsPlansTable.vue";

const props = defineProps({
    periods: {
        type: Object,
        default: () => []
    },
    microsite:{
        type: Object,
        default: () => []
    },
    subscriptionPlans:{
        type: Object,
        default: () => []
    },
    currency:{
        type: Object,
        default: () => []
    }
});

const headers = [
    "name",
    "amount",
    "subscription_period",
    "expiration_time"
];

const isModalOpen = ref(false);

const openModal = () => {
    isModalOpen.value = true;
};

const handleCloseModal = () => {
    isModalOpen.value = false;
};

</script>

<template>
        <header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('subscription.label') }}</h2>
                <button @click="openModal"
                        class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ $t('subscription.newSubscriptionPlan') }}
                </button>
            </div>
        </header>
       <SubscriptionsPlansTable
           :periods="periods"
           :paginator="subscriptionPlans"
           :microsite="microsite"
           :headers="headers"
           :data="subscriptionPlans.data"
           :currency="currency"
       />

        <Modal v-model:show="isModalOpen" >
            <template v-slot:default>
                <h1 class="text-2xl font-semibold m-4">{{ $t('subscription.newSubscriptionPlan') }}</h1>
                <Create
                    :periods="periods"
                    :microsite="microsite"
                    :currency="currency"
                    @closeModal="handleCloseModal"
                />
            </template>
        </Modal>
</template>

