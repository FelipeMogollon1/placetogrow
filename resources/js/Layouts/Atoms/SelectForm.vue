<template>
    <div class="relative z-0 w-full mb-5 group">
        <select
            :name="name"
            :id="id"
            :class="selectClass"
            :required="required"
            v-model="selectedValue"
            @change="updateValue"
        >
            <option value="" disabled selected></option>
            <option v-for="option in options" :key="option.value" :value="option.value" :class="optionClass">
                {{ option.text }}
            </option>
        </select>
        <label :for="id" :class="labelClass">{{ label }}</label>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { defineProps, defineEmits, ref, watch, toRefs } from 'vue';

const props = defineProps({
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
    options: {
        type: Array,
        required: true,
        default: () => []
    },
    modelValue: {
        type: String,
        default: ''
    },
    required: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue']);

const { modelValue } = toRefs(props);
const selectedValue = ref(modelValue.value);

watch(modelValue, (newValue) => {
    selectedValue.value = newValue;
});

const updateValue = (event) => {
    emit('update:modelValue', event.target.value);
};

const selectClass = computed(() => 'block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 focus:outline-none focus:ring-0 focus:border-blue-600 peer');
const labelClass = computed(() => 'peer-focus:font-medium absolute text-sm text-gray-500 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6');
const optionClass = 'text-gray-900'; // Clase para asegurar que el texto de las opciones sea visible

</script>
