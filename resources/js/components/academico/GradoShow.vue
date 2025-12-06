<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { useDate } from 'vuetify';

const { t } = useI18n();
const date = useDate();

const props = defineProps(['item', 'accion']);

const titleShow = t('grado._mostrar_');
</script>
<template>
    <v-card class="rounded-t-xl">
        <v-card-title class="border-b-md bg-blue-grey-lighten-3">
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
                    {{ $t('_codigo_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.codigo }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('grado._descripcion_masculino_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.descripcion_masculino }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('grado._descripcion_femenino_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.descripcion_femenino }}
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
