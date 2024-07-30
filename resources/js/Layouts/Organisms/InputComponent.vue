<template>
    <div>
        <label :for="name" class="block text-sm font-medium text-gray-700">{{ label }}</label>
        <input
            v-if="type === 'text' || type === 'email' || type === 'number'"
            :type="type"
            :id="name"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :placeholder="placeholder"
            :class="inputClass"
        />
        <select
            v-if="type === 'select'"
            :id="name"
            :value="modelValue"
            @change="$emit('update:modelValue', $event.target.value)"
            :class="inputClass"
        >
            <option value="" disabled>{{ $t('select') }}</option>
            <option v-for="(option, index) in options" :key="index" :value="option">{{ $t(`documentType.${option}`) }}</option>
            <option v-for="(constant, index) in constants" :key="index" :value="constant">{{ $t(`documentType.${constant}`) }}</option>
        </select>
        <p v-if="errorMessage" class="text-red-500 text-xs mt-1">{{ errorMessage }}</p>
    </div>
</template>

<script>
export default {
    props: {
        type: {
            type: String,
            required: true,
        },
        name: {
            type: String,
            required: true,
        },
        modelValue: {
            type: [String, Number],
            default: '',
        },
        placeholder: {
            type: String,
            default: '',
        },
        label: {
            type: String,
            default: '',
        },
        errorMessage: {
            type: String,
            default: '',
        },
        options: {
            type: Array,
            default: () => [],
        },
        constants: {
            type: Array,
            default: () => [],
        },
        inputClass: {
            type: String,
            default: 'w-full mt-1 text-sm border-gray-300 rounded-md py-1 px-2',
        },
    },
    methods: {
        logProps() {
            console.log('Model Value:', this.modelValue);
            console.log('Type:', this.type);
            console.log('Options:', this.options);
        }
    },
    mounted() {
        this.logProps();
    }
}
</script>

