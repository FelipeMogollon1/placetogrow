<script setup>
import FileInput from "@/Components/FileInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
</script>

<template>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="flex justify-center p-6 text-gray-900">

                    <form class="w-full max-w-md space-y-5 bg-gray-50 p-8 rounded-lg shadow-lg">
                        <h1 class="text-center text-2xl font-semibold mb-6">Formulario de pago</h1>

                        <div>
                            <InputLabel for="name" value="Nombre" />
                            <input id="name" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>

                        <div>
                            <InputLabel for="surname" value="Apellido" />
                            <input id="surname" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>

                        <div>
                            <InputLabel for="document" value="Documento" />
                            <input id="document" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>

                        <div>
                            <InputLabel for="reference" value="Referencia" />
                            <input id="reference" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>

                        <div>
                            <InputLabel for="total" value="Total a pagar" />
                            <input id="total" type="text" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        </div>

                        <div class="flex justify-center">
                            <PrimaryButton>
                                Iniciar pago
                            </PrimaryButton>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>
