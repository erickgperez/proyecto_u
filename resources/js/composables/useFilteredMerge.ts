export function useFilteredMerge() {
    /**
     * Igual que filteredMerge, pero modifica directamente "target".
     */
    function filteredAssign<T extends Record<string, any>>(
        target: T,
        source: Partial<T>
    ): T {
        for (const key in target) {
            if (key in source) {
                target[key] = source[key] as T[typeof key];
            }
        }
        return target;
    }

    return { filteredAssign };
}
