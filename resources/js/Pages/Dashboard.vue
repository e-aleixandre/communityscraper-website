<template>
    <Head title="Mi Panel" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mi Panel
            </h2>
        </template>

        <dashboard-container>
            <dashboard-notification type="info">
                Este es tu panel. De momento, por las pocas opciones que hay, no sé qué poner aquí. Más adelante se me ocurrirá algo.
            </dashboard-notification>
        </dashboard-container>
    </BreezeAuthenticatedLayout>
</template>

<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue'
import { Head } from '@inertiajs/inertia-vue3';
import DashboardContainer from "@/Components/UI/DashboardContainer";
import DashboardNotification from "@/Components/Notification/DashboardNotification";

export default {
    components: {
        DashboardNotification,
        DashboardContainer,
        BreezeAuthenticatedLayout,
        Head,
    },
}
</script>
