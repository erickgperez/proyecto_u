<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { useDate } from 'vuetify';

const { t } = useI18n();
const date = useDate();

const props = defineProps(['item', 'accion']);

const titleShow = t('usuario._mostrar_');
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
                    {{ $t('usuario._nombre_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.name }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('usuario._email_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.email }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('usuario._asignado_a_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.personas.length > 0 ? props.item.personas[0].nombreCompleto : '' }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('rol._plural_') }}
                </v-col>
                <v-col cols="8">
                    <v-list density="compact" class="transparent-list" :items="props.item.roles" item-title="name"> </v-list>
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
