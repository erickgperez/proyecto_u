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
                    {{ $t('_distrito_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.distrito }}
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
