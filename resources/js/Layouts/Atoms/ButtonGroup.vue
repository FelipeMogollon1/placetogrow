<template>
    <div class="inline-flex rounded-md shadow-sm p-1 border border-transparent" role="group">
        <button
            v-for="(button, index) in buttons"
            :key="index"
            type="button"
            class="inline-flex items-center px-2 py-1 text-sm font-medium text-gray-600 bg-transparent border border-gray-100"
            :class="getButtonClasses(index)"
            @click="button.onClick(item)"
        >
            <span class="material-symbols-outlined">
                {{ button.icon }}
            </span>
        </button>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    buttons: {
        type: Array,
        required: true,
        default: () => []
    },
    item: {
        type: Object,
        required: true
    }
});

const getButtonClasses = (index) => {
    if (index === 0) return 'rounded-s-lg hover:bg-gray-900 hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-gray-900 focus:text-white';
    if (index === props.buttons.length - 1) return 'rounded-e-lg hover:bg-gray-900 hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-gray-900 focus:text-white';
    return 'hover:bg-gray-900 hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-gray-900 focus:text-white';
};
</script>
