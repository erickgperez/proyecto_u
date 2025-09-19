import { useI18n } from 'vue-i18n';

export function useAccionesObject() {
    const { t } = useI18n();

    const accionEditObject = {
        title: t('_editar_'),
        text: t('_editar_datos_registro_seleccionado_'),
        emitAction: 'edit',
        avatarColor: 'blue-darken-2',
        icon: 'mdi-text-box-edit',
    };

    const accionShowObject = {
        title: t('_ver_'),
        text: t('_mostrar_datos_solo_lectura_'),
        emitAction: 'show',
        avatarColor: 'teal-lighten-4',
        icon: 'mdi-eye-outline',
    };

    const accionDeleteObject = {
        title: t('_eliminar_'),
        text: t('_borrar_registro_seleccionado_'),
        emitAction: 'delete',
        avatarColor: 'red-lighten-1',
        icon: 'mdi-delete-alert',
    };

    return {
        accionEditObject,
        accionShowObject,
        accionDeleteObject,
    };
}
