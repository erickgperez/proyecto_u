<script setup lang="ts">
import { useDate } from 'vuetify';

const date = useDate();

const props = defineProps(['item', 'accion']);
</script>
<template>
    <v-card :title="$t('permiso._mostrar_')">
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
                    {{ date.format(props.item.created_at, 'keyboardDateTime12h') }}
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
        </template>
    </v-card>
</template>
