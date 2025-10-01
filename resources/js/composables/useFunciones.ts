export function useFunciones() {
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

    return {
        carrerasAgrupadasVList,
    };
}
