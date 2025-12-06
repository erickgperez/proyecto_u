<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { useDate } from 'vuetify';

const { t } = useI18n();
const date = useDate();

const props = defineProps(['item', 'accion']);

const titleShow = t('rol._mostrar_');
</script>
<template>
    <v-card class="rounded-t-xl">
        <v-card-title class="border-b-md bg-blue-grey-lighten-3 text-blue-darken-3">
            {{ titleShow }}
        </v-card-title>
        <v-card-text>
            <v-row>
                <v-col cols="4">
                    {{ $t('_id_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.id }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('rol._nombre_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.name }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('rol._permisos_') }}
                </v-col>
                <v-col cols="8">
                    <v-list density="compact">
                        <v-list-item v-for="permiso in props.item.permisos" :key="permiso.id" prepend-icon="mdi-circle-small">
                            <v-list-item-title>{{ permiso.name }}</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="4">
                    {{ $t('_created_at_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.created_at ? date.format(props.item.created_at, 'keyboardDateTime12h') : '' }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('_updated_at_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.updated_at ? date.format(props.item.updated_at, 'keyboardDateTime12h') : '' }}
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>
