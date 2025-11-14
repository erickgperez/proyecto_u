/**
 * useDeepFilter.ts
 * Filtro avanzado para Vuetify v-data-table
 * - Busca en objetos y arrays anidados
 * - Ignora tildes y mayúsculas
 * - Compatible con group-by
 * - Permite múltiples palabras con modo AND / OR
 */

export type MultiWordMode = 'AND' | 'OR';

export interface DeepFilterOptions {
    /**
     * Determina cómo combinar múltiples palabras:
     * - "AND" → todas las palabras deben aparecer (por defecto)
     * - "OR"  → basta con que aparezca una
     */
    multiWordMode?: MultiWordMode;
    headers?: Array<[]>;
    groupBy?: Array<[]>;
}

export function useDeepFilter(options: DeepFilterOptions = { multiWordMode: 'AND', headers: [], groupBy: [] }) {
    /*
     * Recuperar valores de objetos que pudieran estar de la forma
     * obj[subobj.propiedad]
     * Transformando a la forma obj.subobj.propiedad
     */
    const getByPath = (obj: any, path: string): string | undefined => {
        if (obj == null || !path) return undefined;
        return path.split('.').reduce((acc: any, seg: string) => {
            if (acc == null) return undefined;
            return acc[seg];
        }, obj);
    };

    /**
     * Normaliza texto:
     * convierte a minúsculas y elimina acentos/tildes
     */
    const normalizeText = (text: unknown): string => {
        return String(text ?? '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '') // elimina tildes
            .trim();
    };

    /**
     * Aplana un objeto o array en un arreglo de strings normalizados
     */
    const flattenValues = (obj: unknown, acc: string[] = []): string[] => {
        if (obj == null) return acc;

        //Esto busca por todo el objeto
        /*if (Array.isArray(obj)) {
            for (const v of obj) flattenValues(v, acc);
        } else if (typeof obj === 'object') {
            for (const v of Object.values(obj)) flattenValues(v, acc);
        } else {
            acc.push(normalizeText(obj));
        }*/
        //No buscar por todo el objeto, solo en las propiedades que están en headers
        const items = options.headers ?? [];
        items.forEach((item) => {
            const raw = getByPath(obj, item.key);
            if (raw) {
                acc.push(normalizeText(raw));
            }
        });

        return acc;
    };

    /**
     * Filtro personalizado para Vuetify v-data-table
     *
     * @param value Valor de la celda (Vuetify lo pasa automáticamente)
     * @param search Texto de búsqueda (v-model del campo search)
     * @param item   Objeto completo del item ({ raw: any, ... })
     */
    const deepFilter = (value: unknown, search: string, item: any): boolean => {
        if (!search) return true;

        const terms = normalizeText(search)
            .split(/\s+/) // divide por espacios
            .filter(Boolean);

        const flatValues = flattenValues(item?.raw ?? item);

        if (options.multiWordMode === 'OR') {
            // Basta con que UNA palabra aparezca en algún campo
            return terms.some((term) => flatValues.some((v) => v.includes(term)));
        } else {
            // Todas las palabras deben aparecer en algún campo
            return terms.every((term) => flatValues.some((v) => v.includes(term)));
        }
    };

    return { deepFilter, getByPath };
}

export type DeepFilter = ReturnType<typeof useDeepFilter>['deepFilter'];
