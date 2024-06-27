<template>
    <div class="relative z-0 w-full mb-5 group">
        <label :for="id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-900">{{ label }}</label>
        <div class="w-full">
            <input
                type="file"
                :name="name"
                :id="id"
                class="sr-only"
                ref="fileInput"
                :required="required"
                @change="handleFileUpload"
            />
            <label for="file-input" class="block w-full px-4 py-2 bg-white dark:bg-neutral-900 border border-gray-200 shadow-sm rounded-lg text-sm font-medium text-gray-700 dark:text-gray-400 cursor-pointer hover:bg-gray-100 dark:hover:bg-neutral-800 text-center">
                <span class="material-icons-outlined text-gray-500 dark:text-gray-400 mr-2"></span> <!-- Icono para adjuntar archivo -->
                {{ buttonLabel }}
            </label>
        </div>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-900" id="file_input_help">Formatos permitidos: SVG, PNG, JPG o GIF (máx. 800x400px).</p>
    </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { defineProps, defineEmits } from 'vue';

const props = defineProps({
    modelValue: [String, Number, File],
    type: {
        type: String,
        default: 'file'
    },
    name: {
        type: String,
        required: true
    },
    id: {
        type: String,
        required: true
    },
    label: {
        type: String,
        required: true
    },
    required: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue']);

const fileInput = ref(null);

const buttonLabel = computed(() => props.modelValue ? 'Cambiar archivo' : 'Subir archivo');

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    emit('update:modelValue', file);
};

const triggerFileInput = () => {
    fileInput.value.click();
};
</script>
