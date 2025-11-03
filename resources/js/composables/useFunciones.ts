import Swal from 'sweetalert2';
import { useI18n } from 'vue-i18n';

export function useFunciones() {
    const { t } = useI18n();

    const rules = {
        required: (value: any) => !!value || t('_campo_requerido_'),
        email: (value: any) => {
            const pattern =
                /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return !value || pattern.test(value) || t('_direccion_correo_no_valida_');
        },
        maxLength: (max: number) => (value: any) => !value || value.length <= max || `${t('_longitud_maxima_')}: ${max} ${t('_caracteres_')}`,
    };

    /**
     * Agrupa un array de carreras por tipo, insertando subencabezados y divisores.
     * @param {Array<Object>} carreras El array de objetos de carrera.
     * @returns {Array<Object>} El array de carreras agrupadas.
     */
    function carrerasAgrupadasVList(tiposCarrera: any, carreras: any) {
        // Primero, agrupar las carreras por tipo usando reduce()
        const carrerasPorTipo = carreras.reduce((acc: any, carrera: any) => {
            const tipoId = carrera.tipo_carrera_id;
            if (!acc[tipoId]) {
                acc[tipoId] = {
                    descripcion: tiposCarrera.filter((tipo: any) => tipo.id == tipoId)[0].descripcion,
                    carreras: [],
                };
            }
            acc[tipoId].carreras.push(carrera);
            return acc;
        }, {});

        // Luego, construir el array final con subencabezados y divisores
        const carrerasAgrupadasArray = [];
        for (const tipoId in carrerasPorTipo) {
            if (Object.prototype.hasOwnProperty.call(carrerasPorTipo, tipoId)) {
                const grupo = carrerasPorTipo[tipoId];

                // Agregar el subencabezado
                carrerasAgrupadasArray.push({
                    type: 'subheader',
                    title: grupo.descripcion,
                });

                // Agregar las carreras del grupo
                grupo.carreras.forEach((carrera: any) => {
                    carrerasAgrupadasArray.push({
                        title: carrera.nombreCompleto,
                        value: carrera.id,
                    });
                });

                // Agregar el divisor
                carrerasAgrupadasArray.push({
                    type: 'divider',
                });
            }
        }
        return carrerasAgrupadasArray;
    }

    function mensajeExito(mensaje: string) {
        Swal.fire({
            title: t('_exito_'),
            text: mensaje,
            icon: 'success',
            position: 'top-end',
            showConfirmButton: false,
            timer: 2500,
            toast: true,
        });
    }

    function mensajeError(mensaje: string) {
        Swal.fire({
            title: t('_error_'),
            text: mensaje,
            icon: 'error',
            confirmButtonColor: '#D7E1EE',
        });
    }

    return {
        rules,
        carrerasAgrupadasVList,
        mensajeExito,
        mensajeError,
    };
}
