<script setup lang="ts">
import { useDate } from 'vuetify';

const date = useDate();

const props = defineProps(['item', 'accion']);
</script>
<template>
    <v-card :title="$t('mallaCurricular._mostrar_')">
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
                    {{ $t('carrera._singular_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.carrera.nombreCompleto }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('mallaCurricular._semestre_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.semestre }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('unidadAcademica._singular_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.unidad_academica.nombre }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('area._singular_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.area.descripcion }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('mallaCurricular._obligatoria_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.obligatoria }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('mallaCurricular._requisito_creditos_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.requisito_creditos }}
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
                    {{ (props.item.creator?.name ?? '') + ' ' + (props.item.creator?.email ?? '') }}
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
                    {{ (props.item.updater?.name ?? '') + ' ' + (props.item.updater?.email ?? '') }}
                </v-col>
            </v-row>
        </template>
    </v-card>
</template>
