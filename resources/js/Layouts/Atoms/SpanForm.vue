<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    color: {
        type: String,
        default: 'yellow'
    }
});

const colorClasses = {
    yellow: {
        background: 'bg-yellow-100',
        text: 'text-yellow-600',
        border: 'border-yellow-400'
    },
    orange: {
        background: 'bg-orange-100',
        text: 'text-orange-600',
        border: 'border-orange-400'
    },
    green: {
        background: 'bg-green-100',
        text: 'text-green-600',
        border: 'border-green-400'
    },
    blue: {
        background: 'bg-blue-100',
        text: 'text-blue-600',
        border: 'border-blue-400'
    },
    red: {
        background: 'bg-red-100',
        text: 'text-red-600',
        border: 'border-red-400'
    },
    violet: {
        background: 'bg-violet-100',
        text: 'text-violet-600',
        border: 'border-violet-400'
    },
    cyan: {
        background: 'bg-cyan-100',
        text: 'text-cyan-600',
        border: 'border-cyan-400'
    }

};

const { background, text, border } = colorClasses[props.color] || colorClasses.yellow;
</script>

<template>
<span
    :class="[background, text, border, 'text-sm px-3 rounded-full border']">
    <slot></slot>
</span>
</template>
