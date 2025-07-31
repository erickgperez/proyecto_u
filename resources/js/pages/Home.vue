<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';

import { Head } from '@inertiajs/vue3';

interface Props {
    mustVerifyEmail?: boolean;
    status?: string;
    componente?: string;
    titulo?: string;
}

const props = defineProps<Props>();
</script>

<template>
    <Head title="Bienvenido"> </Head>
    <AppLayout> </AppLayout>
</template>
