<script setup lang="ts">
import type { User } from '@/types';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user as User;
const persona = page.props.auth.persona;
</script>

<template>
    Dashboard de aspirante
    <div>Usuario: {{ user }}</div>
    <BR />
    <div>Bachiller: {{ persona }}</div>
</template>
