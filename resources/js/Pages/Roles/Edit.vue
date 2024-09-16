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
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $t('role.update_role') }}</h2>
                <Link
                    :href="route('roles.index')"
                    class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 focus:bg-orange-700 active:bg-orange-900 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    {{ $t('role.list_role') }}
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden border border-gray-100 sm:rounded-lg shadow-2xl">
                    <div class="p-6 text-gray-900 justify-center flex ">
                        <form class="w-full max-w-3xl py-8 space-y-5" @submit.prevent="submit">
                            <div>
                                <InputLabel for="name" :value="$t('role.name')" />
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

                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                <div class="" v-for="(permissions, group) in groupedPermissions" :key="group">
                                    <div class="bg-white p-4 rounded">
                                        <div class="flex items-center">
                                            <input
                                                type="checkbox"
                                                :id="'group_' + group"
                                                :checked="permissions.every(permission => isPermissionSelected(permission))"
                                                @click="toggleGroup(permissions)"
                                                class="w-4 h-4 text-orange-400 bg-gray-50 border-orange-500 rounded focus:ring-orange-500 focus:ring-2"
                                            />
                                            <InputLabel :for="'group_' + group" :value="$t(`role.group.${group}`)" class="ml-2 text-orange-500 font-black" />
                                        </div>
                                        <div class="mt-4">
                                            <div v-for="permission in permissions" :key="permission" class="flex items-center mt-2">
                                                <input
                                                    type="checkbox"
                                                    :id="permission"
                                                    :value="permission"
                                                    name="permissions[]"
                                                    class="w-4 h-4 text-orange-400 bg-gray-50 border-orange-500 rounded focus:ring-orange-500 focus:ring-2"
                                                    @change="togglePermission(permission)"
                                                    :checked="isPermissionSelected(permission)"
                                                />
                                                <InputLabel :for="permission" :value="$t(`role.${permission}`)" class="ml-2" />

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
