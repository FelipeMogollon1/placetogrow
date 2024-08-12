<template>
    <button
        :class="[
            'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest',
            colorClasses.background,
            colorClasses.text,
            colorClasses.border,
            colorClasses.hover,
            colorClasses.focus,
            colorClasses.active,
            'transition ease-in-out duration-150'
        ]"
    >
        <slot />
    </button>

</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    color: {
        type: String,
        default: 'orange'
    }
});

const colorClasses = computed(() => {
    const colors = {
        yellow: {
            background: 'bg-yellow-200',
            text: 'text-yellow-600',
            border: 'border-yellow-400',
            hover: 'hover:bg-yellow-300',
            focus: 'focus:ring-yellow-500 focus:ring-offset-2',
            active: 'active:bg-yellow-400'
        },
        orange: {
            background: 'bg-orange-500',
            text: 'text-orange-600',
            border: 'border-orange-400',
            hover: 'hover:bg-orange-700',
            focus: 'focus:ring-orange-500 focus:ring-offset-2',
            active: 'active:bg-orange-900'
        },
        green: {
            background: 'bg-green-500',
            text: 'text-green-600',
            border: 'border-green-400',
            hover: 'hover:bg-green-700',
            focus: 'focus:ring-green-500 focus:ring-offset-2',
            active: 'active:bg-green-900'
        },
        lime: {
            background: 'bg-lime-200',
            text: 'text-lime-600',
            border: 'border-lime-400',
            hover: 'hover:bg-lime-300',
            focus: 'focus:ring-lime-500 focus:ring-offset-2',
            active: 'active:bg-lime-400'
        },
        fuchsia: {
            background: 'bg-fuchsia-200',
            text: 'text-fuchsia-600',
            border: 'border-fuchsia-400',
            hover: 'hover:bg-fuchsia-300',
            focus: 'focus:ring-fuchsia-500 focus:ring-offset-2',
            active: 'active:bg-fuchsia-400'
        }        ,
        pink: {
            background: 'bg-pink-200',
            text: 'text-pink-600',
            border: 'border-pink-400',
            hover: 'hover:bg-pink-300',
            focus: 'focus:ring-pink-500 focus:ring-offset-2',
            active: 'active:bg-pink-400'
        },
        blue: {
            background: 'bg-blue-500',
            text: 'text-blue-600',
            border: 'border-blue-400',
            hover: 'hover:bg-blue-700',
            focus: 'focus:ring-blue-500 focus:ring-offset-2',
            active: 'active:bg-blue-900'
        },
        red: {
            background: 'bg-red-500',
            text: 'text-red-600',
            border: 'border-red-400',
            hover: 'hover:bg-red-700',
            focus: 'focus:ring-red-500 focus:ring-offset-2',
            active: 'active:bg-red-900'
        },
        violet: {
            background: 'bg-violet-500',
            text: 'text-violet-600',
            border: 'border-violet-400',
            hover: 'hover:bg-violet-700',
            focus: 'focus:ring-violet-500 focus:ring-offset-2',
            active: 'active:bg-violet-900'
        },
        cyan: {
            background: 'bg-cyan-500',
            text: 'text-cyan-600',
            border: 'border-cyan-400',
            hover: 'hover:bg-cyan-700',
            focus: 'focus:ring-cyan-500 focus:ring-offset-2',
            active: 'active:bg-cyan-900'
        },
        gray: {
            background: 'bg-gray-500',
            text: 'text-gray-600',
            border: 'border-gray-400',
            hover: 'hover:bg-gray-700',
            focus: 'focus:ring-gray-500 focus:ring-offset-2',
            active: 'active:bg-gray-900'
        }
    };

    return colors[props.color] || colors.orange;
});
</script>

