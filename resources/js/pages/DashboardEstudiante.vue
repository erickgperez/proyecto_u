<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <v-row>
        <v-col cols="12" md="3">
            <Link :href="route('dashboard')">
                <v-hover v-slot="{ isHovering, props }">
                    <v-alert
                        border="start"
                        :class="{ 'on-hover-alert': isHovering }"
                        :elevation="isHovering ? 10 : 2"
                        v-bind="props"
                        variant="outlined"
                        prominent
                        icon="mdi-note-plus-outline"
                        color="indigo-darken-2"
                        :title="$t('dashboard._inscripcion_')"
                        :text="$t('dashboard._asignaturas_')"
                    >
                    </v-alert>
                </v-hover>
            </Link>
        </v-col>
        <v-col cols="12" md="3">
            <Link :href="route('dashboard')">
                <v-hover v-slot="{ isHovering, props }">
                    <v-alert
                        border="start"
                        :class="{ 'on-hover-alert': isHovering }"
                        :elevation="isHovering ? 10 : 2"
                        v-bind="props"
                        variant="outlined"
                        prominent
                        icon="mdi-note-plus-outline"
                        color="brown-darken-2"
                        :title="$t('dashboard._expediente_')"
                        :text="$t('dashboard._academico_')"
                    >
                    </v-alert>
                </v-hover>
            </Link>
        </v-col>
        <v-col cols="12" md="3">
            <Link :href="route('dashboard')">
                <v-hover v-slot="{ isHovering, props }">
                    <v-alert
                        border="start"
                        :class="{ 'on-hover-alert': isHovering }"
                        :elevation="isHovering ? 10 : 2"
                        v-bind="props"
                        variant="outlined"
                        prominent
                        icon="mdi-card-account-details-outline"
                        color="orange-darken-4"
                        :title="$t('dashboard._perfil_')"
                    >
                    </v-alert>
                </v-hover>
            </Link>
        </v-col>
        <v-col cols="12" md="3">
            <Link :href="route('dashboard')">
                <v-hover v-slot="{ isHovering, props }">
                    <v-alert
                        border="start"
                        class="ma-0"
                        :class="{ 'on-hover-alert': isHovering }"
                        :elevation="isHovering ? 10 : 2"
                        v-bind="props"
                        variant="outlined"
                        prominent
                        icon="mdi-file-document-outline"
                        color="deep-purple-darken-1"
                        :title="$t('dashboard._documentos_')"
                    >
                    </v-alert>
                </v-hover>
            </Link>
        </v-col>
    </v-row>
</template>
<style scoped>
.on-hover-alert {
    background-color: #7195ad15 !important;
    transition: background-color 0.3s ease;
}
</style>
