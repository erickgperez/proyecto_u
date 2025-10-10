<script setup lang="ts">
import { computed, onMounted, ref } from 'vue';

const emit = defineEmits(['update:modelValue']);

const props = defineProps(['items', 'seleccionados']);

const selected = ref([]);
const itemsNodes = ref([]);
const selectedL = computed(() => itemsNodes.value.filter((item) => selected.value.includes(item.id + '')));

const search = ref('');

onMounted(() => {
    selected.value = props.seleccionados;
    itemsNodes.value = getObjectsWithoutChildren(props.items);
});

function getObjectsWithoutChildren(obj: []): any {
    const results = [];

    // Si no tiene el atributo children agregar al resultado
    if (!obj.hasOwnProperty('children')) {
        results.push(obj);
    }
    // Revisar los otros nodos
    for (const key in obj) {
        // Verificar que es una propiedad y no un objeto
        if (obj.hasOwnProperty(key) && typeof obj[key] === 'object' && obj[key] !== null) {
            // llamada recursiva
            results.push(...getObjectsWithoutChildren(obj[key]));
        }
    }
    return results;
}

function onClickClose(selection: any) {
    selected.value = selected.value.filter((item) => item != selection.id);
    emit('update:modelValue', selected.value);
}
</script>
<template>
    <v-sheet class="pa-1">
        <v-text-field
            v-model="search"
            clear-icon="mdi-close-circle-outline"
            variant="outlined"
            clearable
            flat
            hide-details
            density="compact"
            prepend-inner-icon="mdi-magnify"
            rounded="xl"
            class="w-50"
        ></v-text-field>
    </v-sheet>

    <v-sheet>
        <v-row dense>
            <v-col class="d-flex" cols="12" sm="6">
                <v-treeview
                    style="max-height: 500px"
                    class="w-100 overflow-y-auto"
                    v-model:selected="selected"
                    @update:model-value="$emit('update:modelValue', $event)"
                    :items="props.items"
                    item-value="id"
                    item-title="title"
                    select-strategy="classic"
                    selectable
                    :indent-lines="true"
                    :search="search"
                    open-all
                >
                    <template v-slot:toggle="{ props: toggleProps, isOpen, isSelected, isIndeterminate }">
                        <v-badge :color="isSelected ? 'success' : 'warning'" :model-value="isSelected || isIndeterminate">
                            <template v-slot:badge>
                                <v-icon v-if="isSelected" icon="$complete"></v-icon>
                            </template>
                            <v-btn
                                v-bind="toggleProps"
                                :color="isIndeterminate ? 'warning' : isSelected ? 'success' : 'medium-emphasis'"
                                :variant="isOpen ? 'outlined' : 'tonal'"
                            ></v-btn>
                        </v-badge>
                    </template>
                </v-treeview>
            </v-col>
            <v-divider :vertical="$vuetify.display.mdAndUp" class="my-md-3"></v-divider>

            <v-col cols="12" sm="6">
                <v-card-text>
                    <v-card-title>{{ $t('auth._permisos_asignados_') }}</v-card-title>
                    <div class="">
                        <v-scroll-x-transition group hide-on-leave>
                            <v-chip
                                v-for="selection in selectedL"
                                :key="selection.id"
                                :text="selection.name"
                                color="primary"
                                variant="outlined"
                                size="small"
                                border
                                closable
                                label
                                class="mr-1 mb-1"
                                @click:close="onClickClose(selection)"
                            ></v-chip>
                        </v-scroll-x-transition>
                    </div>
                </v-card-text>
            </v-col>
        </v-row>
    </v-sheet>
</template>
