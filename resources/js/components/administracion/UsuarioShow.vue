<script setup lang="ts">
import { useDate } from 'vuetify';

const date = useDate();

const props = defineProps(['item', 'accion']);
</script>
<template>
    <v-card :title="$t('usuario._mostrar_')">
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
