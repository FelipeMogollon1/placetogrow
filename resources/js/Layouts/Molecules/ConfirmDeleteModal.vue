<template>
    <Modal :show="show" maxWidth="sm" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Confirmar Eliminación
            </h2>
            <p class="mt-4 text-sm text-gray-600">
                ¿Está seguro de que desea eliminar este registro? Esta acción no se puede deshacer.
            </p>
            <div class="mt-6 flex justify-end">
                <button @click="closeModal" class="mr-2 bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    Cancelar
                </button>
                <button @click="confirmDelete" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                    Eliminar
                </button>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    show: {
        type: Boolean,
        required: true
    },
    item: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['close', 'confirm']);

const closeModal = () => {
    emit('close');
};

const confirmDelete = () => {
    emit('confirm', props.item);
};
</script>
