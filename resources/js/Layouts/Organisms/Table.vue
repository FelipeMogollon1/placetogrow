<script setup>

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    headers: {
        type: Array,
        required: true
    }
});

</script>

<template>
    <!-- component -->
    <div class="flex flex-col">
        <div class="overflow-x-auto sm:mx-0.5 lg:mx-0.5">
            <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                <div class="overflow-hidden">
                    <table class="min-w-full">
                        <thead class="bg-gray-200 border-b">
                        <tr>
                            <th v-for="header in headers" :key="header" scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                                {{ header }}
                            </th>
                         </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in data" :key="index" class="bg-white border-b transition duration-300 ease-in-out hover:bg-gray-100">
                                <td v-for="(value, key) in item" :key="key" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ value }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
