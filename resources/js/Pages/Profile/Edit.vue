<script setup>
import { computed } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import DesktopLayout from '@/Layouts/DesktopLayout.vue';
import MobileAppLayout from '@/Layouts/MobileAppLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

const page = usePage();

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

// Determine layout based on user role
const layoutComponent = computed(() => {
  const roles = page.props.auth.user?.roles;
  const isWarga = roles?.includes('warga') && !roles?.includes('super_admin') && !roles?.includes('admin_dinas');
  return isWarga ? MobileAppLayout : DesktopLayout;
});
</script>

<template>
    <Head title="Profile" />

    <component :is="layoutComponent" page-title="Profile" :show-f-a-b="false">
        <div class="space-y-4">
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                />
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <UpdatePasswordForm />
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <DeleteUserForm />
            </div>
        </div>
    </component>
</template>
