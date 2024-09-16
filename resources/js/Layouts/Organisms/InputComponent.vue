<template>
    <div>
        <label v-if="label === 'amount' && currency !== 'BOTH'" :for="name" class="block text-sm font-medium text-gray-700">{{ $t(`form.${label}`) }}  {{ $t(`currencies.${currency}`) }} </label>
        <label v-else :for="name" class="block text-sm font-medium text-gray-700">{{ $t(`form.${label}`) }}</label>
        <input
            v-if="type === 'text' || type === 'email' || type === 'number'"
            :type="type"
            :id="name"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            :placeholder="$t(`form.${placeholder}`)"
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
            <option v-for="(option, index) in options" :key="index" :value="option">
                {{ $t(`documentType.${option}`) }}
            </option>

            <option v-if="name === 'document_type'" v-for="(constant, index) in constants" :key="index" :value="constant">
                {{ $t(`documentType.${constant}`) }}
            </option>

            <option v-if="name === 'currency_type' && currency === 'BOTH' " v-for="(constant, index) in constants.filter(constant => constant !== 'BOTH')" :key="index" :value="constant">
                {{ $t(`currencies.${constant}`) }}
            </option>
            <option v-else :value="currency" >
                {{ $t(`currencies.${currency}`) }}
            </option>

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
        disable: {
            type: Boolean,
            default: false,
        },
        inputClass: {
            type: String,
            default: 'w-full mt-1 text-sm border-gray-300 rounded-md py-1 px-2',
        },
        currency:{
            type: String,
            default: '',
        }
    }
}
</script>
