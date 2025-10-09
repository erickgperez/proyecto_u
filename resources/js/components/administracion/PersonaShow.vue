<script setup lang="ts">
import { useDate } from 'vuetify';

const date = useDate();

const props = defineProps(['item', 'accion']);
</script>
<template>
    <v-card :title="$t('persona._mostrar_')">
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
                    {{ $t('persona._primer_nombre_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.primer_nombre }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('persona._segundo_nombre_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.segundo_nombre }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('persona._tercer_nombre_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.tercer_nombre }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('persona._primer_apellido_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.primer_apellido }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('persona._segundo_apellido_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.segundo_apellido }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('persona._tercer_apellido_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.tercer_apellido }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('persona._fecha_nacimiento_') }}
                </v-col>
                <v-col cols="8">
                    {{ date.format(props.item.fecha_nacimiento, 'keyboardDate') }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('persona._edad_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.edad }}
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="4">
                    {{ $t('persona._sexo_') }}
                </v-col>
                <v-col cols="8">
                    {{ props.item.sexo.descripcion }}
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
                    {{ (props.item.created_by?.name ?? '') + ' ' + (props.item.created_by?.email ?? '') }}
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
                    {{ (props.item.updated_by?.name ?? '') + ' ' + (props.item.updated_by?.email ?? '') }}
                </v-col>
            </v-row>
        </template>
    </v-card>
</template>
