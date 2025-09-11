<script setup lang="ts">
import { useDate } from 'vuetify';

const date = useDate();

const props = defineProps(['item', 'accion']);
</script>
<template>
    <v-card :title="$t('_mostrar_convocatoria_')">
        <template v-slot:text>
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
                    {{ props.item.cuerpo_mensaje }}
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
                    {{ date.format(props.item.created_at, 'keyboardDateTime12h') }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('_created_by_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.created_by.name + ' ' + props.item.created_by.email }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('_updated_at_') }}
                </v-col>
                <v-col cols="8">
                    {{ date.format(props.item.updated_at, 'keyboardDateTime12h') }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('_updated_by_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.updated_by.name + ' ' + props.item.updated_by.email }}
                </v-col>
            </v-row>
        </template>
    </v-card>
</template>
