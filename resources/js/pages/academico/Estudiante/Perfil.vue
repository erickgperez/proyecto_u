<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Estudiante, Persona, Sexo } from '@/types/tipos';
import { Head } from '@inertiajs/vue3';
import { PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import PerfilForm from '@/components/administracion/PerfilForm.vue';
import PerfilDatosContactoForm from '@/components/administracion/PerfilDatosContactoForm.vue';
import PerfilDocumentos from '@/components/administracion/PerfilDocumentos.vue';


const { t } = useI18n();
const tab = ref(null);


const props = defineProps({    
    estudiante: {
        type: Object as PropType<Estudiante>,
        required: true,
    },
    persona: {
        type: Object as PropType<Persona>,
        required: true,
    },
    sexos: {
        type: Array as PropType<Sexo[]>,
        required: true,
    },
    distritosTree: Array,
    tiposDocumento: Array,
});


</script>

<template>
    <Head :title="$t('estudiante._perfil_')"></Head>
    <AppLayout :titulo="$t('estudiante._perfil_')" :subtitulo="$t('estudiante._perfil_descripcion_')" icono="mdi-card-account-details-outline">
        <v-card>
            <v-tabs v-model="tab" align-tabs="center" color="deep-purple-accent-4" stacked>
                <v-tab :value="1">
                    <v-icon icon="mdi-account"></v-icon>
                    <span v-if="!$vuetify.display.mobile">{{ $t('perfil._datos_personales_') }}</span>
                </v-tab>
                <v-tab :value="2">
                    <v-icon icon="mdi-cellphone-sound"></v-icon>
                    <span v-if="!$vuetify.display.mobile">{{ $t('perfil._datos_contacto_') }}</span>
                </v-tab>
                <v-tab :value="3">
                    <v-icon icon="mdi-file-document-outline"></v-icon>
                    <span v-if="!$vuetify.display.mobile">{{ $t('perfil._documentos_') }}</span>
                </v-tab>
            </v-tabs>

            <v-tabs-window v-model="tab">
                <v-tabs-window-item :value="1">
                    <v-container fluid>                        
                        <PerfilForm                            
                            :item="props.persona"
                            perfil="estudiante"
                            accion="edit"
                            :sexos="props.sexos"
                        ></PerfilForm>
                    </v-container>
                </v-tabs-window-item>
                <v-tabs-window-item :value="2">
                    <v-container fluid>
                        <PerfilDatosContactoForm
                            :item="props.persona"
                            perfil="estudiante"
                            accion="edit"
                            :distritosTree="distritosTree"
                        ></PerfilDatosContactoForm>
                    </v-container>
                </v-tabs-window-item>
                <v-tabs-window-item :value="3">
                    <v-container fluid>
                        <PerfilDocumentos
                            :item="props.persona"
                            perfil="estudiante"
                            accion="edit"
                            :tipos-documento="tiposDocumento"
                        ></PerfilDocumentos>
                    </v-container>
                </v-tabs-window-item>
            </v-tabs-window>
        </v-card>
    </AppLayout>
</template>
