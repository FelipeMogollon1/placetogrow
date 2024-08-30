<!-- SubscriptionForm.vue -->
<template>
    <form @submit.prevent="handleSubmit" class="space-y-4 p-6">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nombre de la Suscripción</label>
                <input
                    type="text"
                    id="name"
                    v-model="form.name"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    required
                />
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                <textarea
                    id="description"
                    v-model="form.description"
                    rows="4"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    required
                ></textarea>
            </div>
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700">Precio ($)</label>
                <input
                    type="number"
                    id="price"
                    v-model.number="form.price"
                    step="0.01"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    required
                />
            </div>
            <div>
                <label for="duration" class="block text-sm font-medium text-gray-700">Duración</label>
                <select
                    id="duration"
                    v-model="form.duration"
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                    required
                >
                    <option value="monthly">Mensual</option>
                    <option value="quarterly">Trimestral</option>
                    <option value="yearly">Anual</option>
                </select>
            </div>
        </div>
        <div class="flex justify-end">
            <button
                type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                Crear Suscripción
            </button>
        </div>
    </form>
</template>

<script setup>
import { ref } from 'vue';

const emit = defineEmits(['submit']);

const form = ref({
    name: '',
    description: '',
    price: 0,
    duration: 'monthly',
});

const handleSubmit = () => {
    emit('submit', { ...form.value });
    form.value = { name: '', description: '', price: 0, duration: 'monthly' }; // Clear the form
};
</script>
