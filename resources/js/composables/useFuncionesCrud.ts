import { ref } from 'vue';

export function useFuncionesCrud(itemVacio: any, items: any) {
    const step = ref(1);
    const selectedAction = ref('');
    const localItems = ref([...items]);
    const selectedItem = ref({ ...itemVacio });

    const handleAction = (action: string) => {
        selectAction(action);
        if (action === 'delete') {
            remove();
        } else if (action === 'new') {
            step.value = 3;
        } else {
            step.value++;
        }
    };

    const handleNextStep = (stepValue: number) => {
        step.value = stepValue;
    };

    function remove() {
        const index = localItems.value.findIndex((item) => item.id === selectedItem.value?.id);
        localItems.value.splice(index, 1);

        step.value = 1;
    }

    const selectItem = (item: any) => {
        selectedItem.value = item;

        step.value++;
    };

    const selectAction = (accion: string) => {
        selectedAction.value = accion;
    };

    const handleFormSave = (data: any) => {
        if (selectedAction.value == 'edit') {
            const index = localItems.value.findIndex((item) => item.id === data.id);

            localItems.value[index] = data;
        } else {
            localItems.value.push(data);
            step.value = 1;
        }
    };

    return {
        step,
        selectedAction,
        localItems,
        selectedItem,
        handleAction,
        handleNextStep,
        remove,
        selectItem,
        selectAction,
        handleFormSave,
    };
}
