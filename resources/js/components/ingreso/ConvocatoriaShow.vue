<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { useDate } from 'vuetify';

const { t } = useI18n();
const date = useDate();

const props = defineProps(['item', 'accion']);

const titleShow = t('_mostrar_convocatoria_');
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
                    {{ $t('_fecha_') }}
                </v-col>
                <v-col cols="8">
                    {{ date.format(props.item.fecha, 'keyboardDate') }}
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
                    {{ $t('_descripcion_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.descripcion }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('_cuerpo_mensaje_') }}
                </v-col>
                <v-col cols="8">
                    <div v-html="props.item.cuerpo_mensaje"></div>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('convocatoria._oferta_') }}
                </v-col>
                <v-col cols="8">
                    <v-list density="compact">
                        <v-list-item v-for="(item, i) in props.item.carreras_sedes" :key="i">
                            <template v-slot:prepend>
                                <v-icon icon="mdi-circle-small"></v-icon>
                            </template>

                            <v-list-item-title> {{ item.sede.nombre }} -- {{ item.carrera.nombreCompleto }} </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('_afiche_') }}
                </v-col>
                <v-col cols="8">
                    <span v-if="props.item.afiche != null">
                        <v-avatar color="blue">
                            <v-btn
                                :title="$t('_descargar_afiche_actual_')"
                                color="white"
                                icon="mdi-file-download-outline"
                                variant="text"
                                :href="`/ingreso/afiche/download/${props.item.id}`"
                            ></v-btn>
                        </v-avatar>
                    </span>
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
