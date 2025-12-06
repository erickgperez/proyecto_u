<script setup lang="ts">
import { useFunciones } from '@/composables/useFunciones';
import { useI18n } from 'vue-i18n';
import { useDate } from 'vuetify';

const { t } = useI18n();
const date = useDate();

const { carrerasAgrupadasVList } = useFunciones();

const props = defineProps(['item', 'accion', 'tiposCarrera']);

const titleShow = t('sede._mostrar_');
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
                    {{ $t('_nombre_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.nombre }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('sede._carreras_') }}
                </v-col>
                <v-col cols="8">
                    <v-list density="compact">
                        <v-list density="compact" class="transparent-list" :items="carrerasAgrupadasVList(props.tiposCarrera, props.item.carreras)">
                        </v-list>
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
                    {{ $t('_created_by_') }}
                </v-col>
                <v-col cols="8">
                    {{ (props.item.creator?.name ?? '') + ' ' + (props.item.creator?.email ?? '') }}
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
            <v-row>
                <v-col cols="4">
                    {{ $t('_updated_by_') }}
                </v-col>
                <v-col cols="8">
                    {{ (props.item.updater?.name ?? '') + ' ' + (props.item.updater?.email ?? '') }}
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>
