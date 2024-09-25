<script setup>
import {Head, Link, router, useForm, usePage} from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {MagnifyingGlassIcon, PhotoIcon} from "@heroicons/vue/24/outline/index.js";
import {ref, computed, watch, watchEffect} from "vue";
import { route } from "ziggy-js";
import InvoiceTable from "@/Layouts/Organisms/InvoiceTable.vue";
import {Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions} from "@headlessui/vue";
import {CheckIcon, ChevronUpDownIcon, InboxArrowDownIcon} from "@heroicons/vue/20/solid/index.js";
import {debounce} from "@/Utils/debounce.js";
import { useI18n } from 'vue-i18n';
import InvoiceUploadTable from "@/Layouts/Organisms/InvoiceUploadTable.vue";

const { t } = useI18n();

defineProps({
    invoices: {
        type: Object,
        default: () => []
    },
    microsites: {
        type: Object,
        default: () => []
    },
    uploadInvoice:{
        type: Object,
        default: () => []
    }
});

const search = ref(usePage().props.filter.search),
    pageNumber = ref(1);

const paymentsUrl = computed(() => {
    const url = new URL(route("invoices.index"))

    if(search.value){
        url.searchParams.append("search", search.value)
    }
    return url;
});

const debouncedUpdateUrl = debounce((updatedPaymentsUrl) => {
    router.visit(updatedPaymentsUrl, {
        preserveScroll: true,
        preserveState: true,
        replace: true
    });
}, 300);

watch(
    () => paymentsUrl.value,
    (updatedPaymentsUrl) => {
        debouncedUpdateUrl(updatedPaymentsUrl);
    }
);

const headers = [
    "reference",
    "microsite_name",
    "name",
    "surname",
    "document_type",
    "document",
    "currency_type",
    "amount",
    "status",
];

const selected = ref(null);

const form = useForm({
    invoices: null,
    microsite_id: null
});

const fileInput = ref(null);


const handleButtonClick = () => {
    fileInput.value?.click();
};


const handleFileChange = (event) => {
    const file = event.target.files?.[0];
    if (file) {
        form.invoices = file;
    }
};

const fileName = computed(() => form.invoices ? form.invoices.name : t('invoices.noFile'));

const submitForm = () => {
    if (selected.value) {
        form.microsite_id = selected.value.id;
    }

    form.post(route('invoices.import'), {
        onSuccess: () => {

        },
        onError: (error) => {

        },
        forceFormData: true
    });
};

const downloadTemplate = () => {
    window.location.href = route('invoices.download-template');
};


const page = usePage();
const importErrors = ref( [])

watchEffect(() => {
    importErrors.value = page.props.flash.importErrors || [];
});

const headersUploadInvoice = [
    "microsite",
    "name",
    "storage_path",
    "error_file_path",
    "created_at",
];

</script>

<template>
    <Head :title="$t('invoices.title')" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{$t('invoices.title')}}</h2>
            </div>
        </template>

        <form v-if="can('invoices.import')" @submit.prevent="submitForm" class="space-y-6 p-6 mt-5 bg-white rounded-lg shadow-md ">

            <div v-if="importErrors.length > 0" class="bg-red-100 text-red-700 p-4 mb-4 rounded-lg">
                <p class="font-bold mb-2">Errores encontrados durante la importación:</p>
                <ul class="space-y-4">
                    <li v-for="(errorData, index) in importErrors" :key="index">
                        <div class="mb-2">
                            <p><strong>Fila {{ index + 1 }}:</strong></p>
                            <ul class="ml-4">
                                <li v-for="(value, key) in errorData.row" :key="key">
                                    <strong>{{ key }}:</strong> {{ value }}
                                </li>
                            </ul>
                        </div>

                        <div class="ml-4 text-sm text-red-600">
                            <ul>
                                <li v-for="(fieldErrors, field) in errorData.errors" :key="field">
                                    <strong>Error en el campo {{ field }}:</strong>
                                    <ul class="ml-4 list-disc">
                                        <li v-for="(message, messageIndex) in fieldErrors" :key="messageIndex">
                                            {{ message }}
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>



            <div class="flex justify-between">
                <p class="text-gray-700 mb-2">{{ $t('invoices.selectImport') }}</p>
                <button
                    @click="downloadTemplate"
                    class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ $t('invoices.downloadTemplate') }}
                    <InboxArrowDownIcon class="ml-4 w-6 text-gray-50" />
                </button>
            </div>




            <div class="flex items-center space-x-10">

                <div class="flex-grow">
                    <label class="flex items-center cursor-pointer">
                    <span class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ $t('invoices.selectFile') }}
                    </span>
                        <input
                            ref="fileInput"
                            type="file"
                            accept=".csv, .xlsx, .xls"
                            class="hidden"
                            @change="handleFileChange"
                            required
                        />
                    </label>
                    <span class="text-gray-600">{{ fileName }}</span>
                    <p class="text-red-500" v-if="form.errors.invoices">{{ form.errors.invoices }}</p>
                </div>

                  <div class="flex-grow">
                    <Listbox as="div" v-model="selected">
                        <ListboxLabel v-if="selected" class="block text-sm font-medium leading-6 text-gray-900">
                            {{ $t('invoices.selectMicrosite') }}
                        </ListboxLabel>
                        <div class="relative mt-2">
                            <ListboxButton class="relative w-full cursor-default rounded-md bg-white py-2 pl-3 pr-10 text-left text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:outline-none focus:ring-2 focus:ring-orange-500 sm:text-sm sm:leading-6">
                                <div v-if="selected">
                        <span class="flex items-center">
                            <img v-if="selected.logo" class="h-6 w-6 flex-shrink-0 rounded-full" :src="`/storage/${selected.logo}`" alt="Logo">
                            <PhotoIcon v-else class="h-6 w-6 flex-shrink-0 rounded-full"/>
                            <span class="ml-3 block truncate">{{ selected.name }}</span>
                        </span>
                                    <span class="pointer-events-none absolute inset-y-0 right-0 ml-3 flex items-center pr-2">
                            <ChevronUpDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true" />
                        </span>
                                </div>
                                <div v-else>
                                    <span class="ml-3 block truncate">{{ $t('invoices.selectMicrosite') }}</span>
                                </div>
                            </ListboxButton>

                            <transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
                                <ListboxOptions class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
                                    <ListboxOption as="template" v-for="person in microsites" :key="person.id" :value="person" v-slot="{ active, selected }">
                                        <li :class="[active ? 'bg-orange-600 text-white' : 'text-gray-900', 'relative cursor-default select-none py-2 pl-3 pr-9']">
                                            <div class="flex items-center">
                                                <img v-if="person.logo" class="h-6 w-6 flex-shrink-0 rounded-full" :src="`/storage/${person.logo}`" alt="Logo">
                                                <PhotoIcon v-else class="h-6 w-6 flex-shrink-0 rounded-full"/>
                                                <span :class="[selected ? 'font-semibold' : 'font-normal', 'ml-3 block truncate']">{{ person.name }}</span>
                                            </div>
                                            <span v-if="selected" :class="[active ? 'text-white' : 'text-orange-600', 'absolute inset-y-0 right-0 flex items-center pr-4']">
                                    <CheckIcon class="h-5 w-5" aria-hidden="true" />
                                </span>
                                        </li>
                                    </ListboxOption>
                                </ListboxOptions>
                            </transition>
                        </div>
                    </Listbox>
                    <input type="hidden" name="microsite_id" :value="selected?.id" />
                      <p class="text-red-500" v-if="form.errors.microsite_id">{{ form.errors.microsite_id }}</p>
                </div>
            </div>

            <div class="flex justify-center">
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ $t('invoices.matter') }}
                </button>
            </div>
            <InvoiceUploadTable :data="uploadInvoice.data" :headers="headersUploadInvoice"  :paginator="uploadInvoice"/>
        </form>


   <div class="max-w-sm mx-auto relative mt-6">
               <input type="text"
                      v-model="search"
                      class="py-2 px-11 block w-full border-gray-300 focus:border-gray-500 focus:ring-gray-500 rounded-full text-sm"
                      :placeholder="$t('invoices.searchInvoice')">
               <MagnifyingGlassIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-6 text-gray-600" />
           </div>
        <InvoiceTable :data="invoices.data" :paginator="invoices" :headers="headers" />
    </AuthenticatedLayout>
</template>
