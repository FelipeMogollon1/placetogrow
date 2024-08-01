<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { route } from "ziggy-js";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { watch, computed } from "vue";

const props = defineProps({
    role: {
        type: Object,
        required: true,
    },
    allPermissions: {
        type: Array,
        required: true,
    }
});

const form = useForm({
    name: props.role.name,
    permissions: props.role.permissions.map(permission => permission.name) || [],
});


const isPermissionSelected = (permission) => {
    return form.permissions.includes(permission);
};


const togglePermission = (permission) => {
    if (isPermissionSelected(permission)) {
        form.permissions = form.permissions.filter(p => p !== permission);
    } else {
        form.permissions.push(permission);
    }
};

const toggleGroup = (permissions) => {
    const allSelected = permissions.every(permission => isPermissionSelected(permission));
    if (allSelected) {
        form.permissions = form.permissions.filter(p => !permissions.includes(p));
    } else {
        permissions.forEach(permission => {
            if (!isPermissionSelected(permission)) {
                form.permissions.push(permission);
            }
        });
    }
};

const groupedPermissions = computed(() => {
    const groups = {};
    props.allPermissions.forEach(permission => {
        const [entity] = permission.name.split('.');
        if (!groups[entity]) {
            groups[entity] = [];
        }
        groups[entity].push(permission.name);
    });
    return groups;
});

watch(() => props.role, (newRole) => {
    form.name = newRole.name;
    form.permissions = newRole.permissions.map(permission => permission.name) || [];
}, { immediate: true });

const submit = () => {
    form.put(route('roles.update', props.role.id));
};
</script>

<template>
    <Head title="Actualizar Rol"/>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Actualizar Rol</h2>
                <Link
                    :href="route('roles.index')"
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Lista de roles
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="flex justify-center p-6 text-gray-900">
                        <form class="w-full max-w-3xl py-8 space-y-5" @submit.prevent="submit">
                            <div>
                                <InputLabel for="name" value="Nombre" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    autofocus
                                    autocomplete="name"
                                    placeholder="Pedro Jose"
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                <div class="" v-for="(permissions, group) in groupedPermissions" :key="group">
                                    <div class="bg-white p-4 rounded shadow-md">
                                        <div class="flex items-center">
                                            <input
                                                type="checkbox"
                                                :id="'group_' + group"
                                                :checked="permissions.every(permission => isPermissionSelected(permission))"
                                                @click="toggleGroup(permissions)"
                                            />
                                            <InputLabel :for="'group_' + group" :value="group" class="ml-2" />
                                        </div>
                                        <div class="mt-4">
                                            <div v-for="permission in permissions" :key="permission" class="flex items-center mt-2">
                                                <input
                                                    type="checkbox"
                                                    :id="permission"
                                                    :value="permission"
                                                    name="permissions[]"
                                                    class="form-checkbox"
                                                    @change="togglePermission(permission)"
                                                    :checked="isPermissionSelected(permission)"
                                                />
                                                <InputLabel :for="permission" :value="permission" class="ml-2" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-center">
                                <PrimaryButton>
                                    Actualizar Rol
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
