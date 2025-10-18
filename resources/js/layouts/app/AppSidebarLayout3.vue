<script setup lang="ts">
import { usePermissions } from '@/composables/usePermissions';
import type { BreadcrumbItemType } from '@/types';
import { type User } from '@/types';
import { Link, router, usePage } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import { useLocale } from 'vuetify';

const { hasPermission, hasAnyPermission } = usePermissions();

const page = usePage();
const user = page.props.auth.user as User;

const { current } = useLocale();

function changeLocale(locale: string) {
    current.value = locale;
}

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
    titulo?: string;
    subtitulo?: string;
    icono?: string;
}
const drawer = ref(true);
//const group = ref(null);

const menu = ref(false);
const menuModulos = ref(false);
const modulos = [
    {
        codigo: 'administracion',
        nombre: 'modulo._administracion_',
        icono: 'mdi-office-building-cog-outline',
    },
    {
        codigo: 'ingreso-universitario',
        nombre: 'modulo._ingreso_universitario_',
        icono: 'mdi-account-plus-outline',
    },
    {
        codigo: 'gestion-academica',
        nombre: 'modulo._gestion_academica_',
        icono: 'mdi-file-document-edit-outline',
    },
    {
        codigo: 'calificaciones',
        nombre: 'modulo._calificaciones_',
        icono: 'mdi-book-education-outline',
    },
];
interface Modulo {
    codigo: string;
    nombre: string;
    icono: string;
}
const moduloActual = ref<Modulo | null>(null);

const handleLogout = () => {
    router.flushAll();
};

watch(moduloActual, (newValue) => {
    if (newValue != null) {
        localStorage.setItem('confUser-' + user.id, JSON.stringify({ userId: user.id, modulo: newValue.codigo }));
    }
});

const props = withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
    titulo: () => '',
    subtitulo: () => '',
    icono: () => '',
});

onMounted(() => {
    const confUser = localStorage.getItem('confUser-' + user.id);

    if (confUser) {
        const confUserObj = JSON.parse(confUser);
        [moduloActual.value] = modulos.filter((modulo) => modulo.codigo === confUserObj.modulo);
    } else {
        moduloActual.value = modulos[0];
    }
});
</script>

<template>
    <v-responsive>
        <v-app>
            <v-navigation-drawer v-model="drawer" :location="$vuetify.display.mobile ? 'bottom' : undefined" class="bg-blue-grey-darken-1">
                <v-list class="text-white">
                    <Link :href="route('dashboard')">
                        <v-list-item prepend-icon="mdi-school" :title="page.props.name">
                            <template v-slot:prepend>
                                <v-avatar>
                                    <v-icon color="info" icon="mdi-school" size="x-large"></v-icon>
                                </v-avatar>
                            </template>
                        </v-list-item>
                    </Link>
                </v-list>
                <v-divider></v-divider>

                <v-list density="compact" nav>
                    <!-- <Link :href="route('informe-example')"    >
                        <v-list-item
                            prepend-icon="mdi-text-box-multiple-outline"
                            class="text-body-1"
                            :class="$page.url === '/informe/example' ? 'bg-blue-lighten-4' : ''"
                            @click="handleLinkClick('informe-example')"
                        >
                            {{ $t('_informe_') }}
                        </v-list-item>
                    </Link>
                    <Link :href="route('crud-example')"    >
                        <v-list-item
                            prepend-icon="mdi-form-textbox"
                            class="text-body-1"
                            :class="$page.url === '/crud/example' ? 'bg-blue-lighten-4' : ''"
                            @click="handleLinkClick('crud-example')"
                        >
                            CRUD
                        </v-list-item>
                    </Link>

                    <Link :href="route('profile.edit')"    >
                        <v-list-item
                            prepend-icon="mdi-cog"
                            :class="$page.url === '/settings/profile' ? 'bg-blue-lighten-4' : ''"
                            @click="handleLinkClick('profile.edit')"
                        >
                            {{ $t('_configuracion_') }}
                        </v-list-item>
                    </Link> -->
                    <v-sheet color="transparent" v-if="hasPermission('MODULO_INGRESO') && moduloActual?.codigo == 'ingreso-universitario'">
                        <v-list-item
                            v-if="hasPermission('MENU_INGRESO_CONVOCATORIA')"
                            prepend-icon="mdi-book-outline"
                            append-icon="mdi-menu-right"
                            class="text-body-1 text-none text-left"
                        >
                            {{ $t('convocatoria._convocatoria_') }}
                            <v-menu activator="parent">
                                <v-list class="bg-blue-grey-darken-2">
                                    <Link
                                        :href="route('ingreso-convocatoria-index')"
                                        v-if="hasPermission('MENU_INGRESO_CONVOCATORIA_GESTIONAR-CONVOCATORIA')"
                                    >
                                        <v-list-item
                                            link
                                            prepend-icon="mdi-book-settings-outline"
                                            :title="$t('_gestionar_convocatoria_')"
                                            :class="$page.url === '/ingreso/convocatoria' ? 'bg-blue-lighten-4' : ''"
                                        >
                                        </v-list-item>
                                    </Link>
                                    <Link
                                        :href="route('ingreso-bachillerato-cargar-archivo')"
                                        v-if="hasPermission('MENU_INGRESO_CONVOCATORIA_CARGAR-ARCHIVO')"
                                    >
                                        <v-list-item
                                            link
                                            prepend-icon="mdi-upload-circle-outline"
                                            :title="$t('_cargar_archivo_')"
                                            :class="$page.url === '/ingreso/bachillerato/cargar-archivo' ? 'bg-blue-lighten-4' : ''"
                                        >
                                        </v-list-item>
                                    </Link>
                                    <Link
                                        v-if="hasPermission('MENU_INGRESO_CONVOCATORIA_CANDIDATOS')"
                                        :href="route('ingreso-bachillerato-candidatos')"
                                    >
                                        <v-list-item
                                            link
                                            prepend-icon="mdi-account-star-outline"
                                            :title="$t('_candidatos_')"
                                            :class="$page.url === '/ingreso/bachillerato/candidatos' ? 'bg-blue-lighten-4' : ''"
                                        >
                                        </v-list-item>
                                    </Link>
                                </v-list>
                            </v-menu>
                        </v-list-item>
                        <Link
                            v-if="hasPermission('MENU_INGRESO_CALIFICACION-BACHILLERATO')"
                            :href="route('ingreso-bachillerato-cargar-calificacion')"
                        >
                            <v-list-item
                                link
                                prepend-icon="mdi-card-account-details-star-outline"
                                :class="$page.url === '/ingreso/bachillerato/cargar-calificacion' ? 'bg-blue-lighten-4' : ''"
                            >
                                {{ $t('ingreso._nota_bachillerato_') }}
                            </v-list-item>
                        </Link>
                        <v-list-item
                            v-if="hasPermission('MENU_INGRESO_ASPIRANTES')"
                            prepend-icon="mdi-account-convert"
                            append-icon="mdi-menu-right"
                            class="text-body-1 text-none text-left"
                        >
                            {{ $t('aspirante._plural_') }}
                            <v-menu activator="parent">
                                <v-list class="bg-blue-grey-darken-2">
                                    <Link :href="route('ingreso-aspirante-seleccion')" v-if="hasPermission('MENU_INGRESO_ASPIRANTES_SELECCION')">
                                        <v-list-item
                                            link
                                            prepend-icon="mdi-account-filter-outline"
                                            :title="$t('aspirante._seleccion_')"
                                            :class="$page.url === '/ingreso/aspirante/seleccion' ? 'bg-blue-lighten-4' : ''"
                                        >
                                        </v-list-item>
                                    </Link>
                                </v-list>
                            </v-menu>
                        </v-list-item>
                    </v-sheet>
                    <v-sheet color="transparent" v-if="hasPermission('MODULO_ADMINISTRACION') && moduloActual?.codigo == 'administracion'">
                        <Link v-if="hasPermission('MENU_ADMINISTRACION_PERSONA')" :href="route('administracion-persona-index')">
                            <v-list-item
                                link
                                prepend-icon="mdi-account-group-outline"
                                :class="$page.url === '/administracion/persona' ? 'bg-blue-lighten-4' : ''"
                            >
                                {{ $t('persona._plural_') }}
                            </v-list-item>
                        </Link>
                        <v-list-item
                            v-if="hasPermission('MENU_ADMINISTRACION_SEGURIDAD_')"
                            prepend-icon="mdi-security"
                            append-icon="mdi-menu-right"
                            class="text-body-1 text-none text-left"
                        >
                            {{ $t('_seguridad_') }}
                            <v-menu activator="parent">
                                <v-list class="bg-blue-grey-darken-2">
                                    <Link :href="route('seguridad-usuario-index')" v-if="hasPermission('MENU_ADMINISTRACION_SEGURIDAD_USUARIO')">
                                        <v-list-item
                                            link
                                            prepend-icon="mdi-card-account-details-outline"
                                            :title="$t('usuario._plural_')"
                                            :class="$page.url === '/seguridad/usuario' ? 'bg-blue-lighten-4' : ''"
                                        >
                                        </v-list-item>
                                    </Link>
                                    <Link :href="route('seguridad-roles-index')" v-if="hasPermission('MENU_ADMINISTRACION_SEGURIDAD_ROLES')">
                                        <v-list-item
                                            link
                                            prepend-icon="mdi-account-box"
                                            :title="$t('rol._plural_')"
                                            :class="$page.url === '/seguridad/roles' ? 'bg-blue-lighten-4' : ''"
                                        >
                                        </v-list-item>
                                    </Link>
                                    <Link :href="route('seguridad-permisos-index')" v-if="hasPermission('MENU_ADMINISTRACION_SEGURIDAD_PERMISOS')">
                                        <v-list-item
                                            link
                                            prepend-icon="mdi-list-status"
                                            :title="$t('permiso._plural_')"
                                            :class="$page.url === '/seguridad/permisos' ? 'bg-blue-lighten-4' : ''"
                                        >
                                        </v-list-item>
                                    </Link>
                                </v-list>
                            </v-menu>
                        </v-list-item>
                        <v-list-item
                            v-if="hasPermission('MENU_ADMINISTRACION_PROCESOS_')"
                            prepend-icon="mdi-archive-cog-outline"
                            append-icon="mdi-menu-right"
                            class="text-body-1 text-none text-left"
                        >
                            {{ $t('_procesos_') }}
                            <v-menu activator="parent">
                                <v-list class="bg-blue-grey-darken-2">
                                    <Link :href="route('procesos-proceso-index')" v-if="hasPermission('MENU_ADMINISTRACION_PROCESOS_PROCESO')">
                                        <v-list-item
                                            link
                                            prepend-icon="mdi-state-machine"
                                            :title="$t('flujo._singular_')"
                                            :class="$page.url === '/procesos/proceso' ? 'bg-blue-lighten-4' : ''"
                                        >
                                        </v-list-item>
                                    </Link>
                                    <Link :href="route('proceso-etapa-index')" v-if="hasPermission('MENU_ADMINISTRACION_PROCESOS_ETAPA')">
                                        <v-list-item
                                            link
                                            prepend-icon="mdi-transit-connection-horizontal"
                                            :title="$t('etapa._singular_')"
                                            :class="$page.url === '/proceso/etapa' ? 'bg-blue-lighten-4' : ''"
                                        >
                                        </v-list-item>
                                    </Link>
                                    <Link :href="route('proceso-transicion-index')" v-if="hasPermission('MENU_ADMINISTRACION_PROCESOS_TRANSICION')">
                                        <v-list-item
                                            link
                                            prepend-icon="mdi-transit-detour"
                                            :title="$t('transicion._singular_')"
                                            :class="$page.url === '/proceso/transicion' ? 'bg-blue-lighten-4' : ''"
                                        >
                                        </v-list-item>
                                    </Link>
                                    <Link :href="route('proceso-tipo-index')" v-if="hasPermission('MENU_ADMINISTRACION_PROCESOS_TIPO-PROCESO')">
                                        <v-list-item
                                            link
                                            prepend-icon="mdi-label-multiple-outline"
                                            :title="$t('tipoFlujo._plural_')"
                                            :class="$page.url === '/proceso/tipo' ? 'bg-blue-lighten-4' : ''"
                                        >
                                        </v-list-item>
                                    </Link>
                                    <Link :href="route('proceso-estado-index')" v-if="hasPermission('MENU_ADMINISTRACION_PROCESOS_ESTADO')">
                                        <v-list-item
                                            link
                                            prepend-icon="mdi-alert-circle-check-outline"
                                            :title="$t('estado._plural_')"
                                            :class="$page.url === '/proceso/estado' ? 'bg-blue-lighten-4' : ''"
                                        >
                                        </v-list-item>
                                    </Link>
                                </v-list>
                            </v-menu>
                        </v-list-item>
                    </v-sheet>
                    <v-sheet color="transparent" v-if="hasPermission('MODULO_GESTION-ACADEMICA') && moduloActual?.codigo == 'gestion-academica'">
                        <Link v-if="hasPermission('MENU_ACADEMICA_SEDES')" :href="route('academica-sede-index')">
                            <v-list-item
                                link
                                prepend-icon="mdi-office-building-cog-outline"
                                :class="$page.url === '/academica/sede' ? 'bg-blue-lighten-4' : ''"
                            >
                                {{ $t('sede._sedes_') }}
                            </v-list-item>
                        </Link>
                        <v-list-item
                            v-if="hasPermission('MENU_ACADEMICA_PLAN-ESTUDIO')"
                            prepend-icon="mdi-collage"
                            append-icon="mdi-menu-right"
                            class="text-body-1 text-none text-left"
                        >
                            {{ $t('_plan_estudio_') }}
                            <v-menu activator="parent">
                                <v-list class="bg-blue-grey-darken-2">
                                    <Link :href="route('plan_estudio-carrera-index')" v-if="hasPermission('MENU_ACADEMICA_PLAN-ESTUDIO_CARRERA')">
                                        <v-list-item
                                            link
                                            prepend-icon="mdi-certificate-outline"
                                            :title="$t('carrera._singular_')"
                                            :class="$page.url === '/plan_estudio/carrera' ? 'bg-blue-lighten-4' : ''"
                                        >
                                        </v-list-item>
                                    </Link>
                                    <Link
                                        :href="route('plan_estudio-tipo_carrera-index')"
                                        v-if="hasPermission('MENU_ACADEMICA_PLAN-ESTUDIO-TIPO_CARRERA')"
                                    >
                                        <v-list-item
                                            link
                                            prepend-icon="mdi-map-legend"
                                            :title="$t('tipoCarrera._tipo_carrera_')"
                                            :class="$page.url === '/plan_estudio/tipo_carrera' ? 'bg-blue-lighten-4' : ''"
                                        >
                                        </v-list-item>
                                    </Link>
                                    <Link :href="route('plan_estudio-grado-index')" v-if="hasPermission('MENU_ACADEMICA_PLAN-ESTUDIO_GRADO')">
                                        <v-list-item
                                            link
                                            prepend-icon="mdi-checkbook"
                                            :title="$t('grado._grado_')"
                                            :class="$page.url === '/plan_estudio/grado' ? 'bg-blue-lighten-4' : ''"
                                        >
                                        </v-list-item>
                                    </Link>
                                </v-list>
                            </v-menu>
                        </v-list-item>
                    </v-sheet>

                    <v-btn
                        v-if="hasPermission('MODULO_CALIFICACIONES') && moduloActual?.codigo == 'calificaciones'"
                        variant="text"
                        append-icon="mdi-menu-right"
                        class="text-body-1 text-none text-left"
                    >
                        {{ $t('_calificaciones_') }}
                        <v-menu activator="parent">
                            <v-list class="bg-blue-grey-darken-2">
                                <v-list-item v-for="i in 3" :key="i" link append-icon="mdi-menu-right">
                                    <v-list-item-title>Calificaciones Item {{ i }}</v-list-item-title>
                                    <v-menu :open-on-focus="false" activator="parent" open-on-hover submenu>
                                        <v-list class="bg-blue-grey-darken-2">
                                            <v-list-item v-for="j in 3" :key="j" link append-icon="mdi-menu-right">
                                                <v-list-item-title>Calificaciones Item {{ i }} - {{ j }}</v-list-item-title>
                                                <v-menu :open-on-focus="false" activator="parent" open-on-hover submenu>
                                                    <v-list class="bg-blue-grey-darken-2">
                                                        <v-list-item v-for="k in 5" :key="k" link>
                                                            <v-list-item-title>Calificaciones Item {{ i }} - {{ j }} - {{ k }}</v-list-item-title>
                                                        </v-list-item>
                                                    </v-list>
                                                </v-menu>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </v-btn>
                </v-list>
            </v-navigation-drawer>
            <v-app-bar :elevation="10" rounded="b-xl">
                <template v-slot:prepend>
                    <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
                </template>
                <template v-slot:append>
                    <v-menu v-if="hasPermission('MODULO_')" v-model="menuModulos" :close-on-content-click="false">
                        <template v-slot:activator="{ props }">
                            <v-btn
                                color="indigo"
                                v-bind="props"
                                stacked
                                :title="moduloActual?.codigo === '' ? $t('modulo._modulos_') : $t('modulo._modulo_actual_')"
                                :prepend-icon="moduloActual?.codigo === '' ? 'mdi-view-module' : moduloActual?.icono"
                            >
                                {{ moduloActual?.codigo === '' ? $t('modulo._modulos_') : $t(moduloActual?.nombre || '') }}
                            </v-btn>
                        </template>

                        <v-card min-width="300">
                            <v-item-group mandatory>
                                <v-container>
                                    <v-row>
                                        <v-col v-for="modulo in modulos" :key="modulo.codigo">
                                            <v-item>
                                                <v-card
                                                    :color="moduloActual?.codigo == modulo.codigo ? 'primary' : ''"
                                                    class="d-flex align-center"
                                                    height="150"
                                                    width="150"
                                                    dark
                                                    @click="moduloActual = modulo"
                                                >
                                                    <v-btn :prepend-icon="modulo.icono" stacked variant="plain" height="150" width="150">
                                                        {{ $t(modulo.nombre) }}
                                                    </v-btn>
                                                </v-card>
                                            </v-item>
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-item-group>
                        </v-card>
                    </v-menu>
                    <v-list>
                        <v-menu v-model="menu" :close-on-content-click="false" location="bottom center">
                            <template v-slot:activator="{ props }">
                                <v-list-item
                                    prepend-icon="mdi-account-settings"
                                    :subtitle="user.email"
                                    :title="user.name"
                                    v-bind="props"
                                ></v-list-item>
                            </template>

                            <v-card rounded="xl">
                                <v-list>
                                    <v-list-item prepend-icon="mdi-cog">
                                        <Link :href="route('profile.edit')" prefetch as="button" :only="['tabContent']">
                                            {{ $t('_configuracion_') }}
                                        </Link>
                                    </v-list-item>

                                    <v-list-item prepend-icon="mdi-logout">
                                        <Link class="block" method="post" :href="route('logout')" @click="handleLogout" as="button">
                                            {{ $t('auth._cerrar_sesion_') }}
                                        </Link>
                                    </v-list-item>
                                    <v-list-item prepend-icon="mdi-translate">
                                        <v-select
                                            v-model="$i18n.locale"
                                            :items="$i18n.availableLocales"
                                            outlined
                                            dense
                                            @update:modelValue="changeLocale"
                                        ></v-select>
                                    </v-list-item>
                                </v-list>
                            </v-card>
                        </v-menu>
                    </v-list>
                </template>

                <v-app-bar-title>
                    <v-card class="mx-auto" :prepend-icon="props.icono">
                        <template v-slot:title>
                            <span class="font-weight-black text-info">{{ props.titulo }}</span>
                        </template>
                        <template v-slot:subtitle>
                            <span class="font-weight-black">{{ props.subtitulo }}</span>
                        </template>
                        <template v-slot:prepend>
                            <v-icon size="x-large" color="info"></v-icon>
                        </template>
                    </v-card>
                </v-app-bar-title>
            </v-app-bar>
            <v-main class="mt-1">
                <v-card class="bg-surface-light mx-auto h-100">
                    <v-card-text class="pt-4">
                        <slot />
                    </v-card-text>
                </v-card>
            </v-main>
        </v-app>
    </v-responsive>
</template>
