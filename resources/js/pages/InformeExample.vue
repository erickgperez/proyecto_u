<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

const shipping = ref(0);
const step = ref(1);
const subtotal = computed(() => products.reduce((acc, product) => acc + product.quantity * product.price, 0));
const total = computed(() => subtotal.value + Number(shipping.value ?? 0));

const items = ['Parámetros', 'Informe'];
const products = [
    {
        name: 'Product 1',
        price: 10,
        quantity: 2,
    },
    {
        name: 'Product 2',
        price: 15,
        quantity: 10,
    },
];

onMounted(() => {
    step.value = 1;
});
</script>

<template>
    <Head title="Informe de ejemplo"> </Head>
    <AppLayout titulo="Plantilla de informe" subtitulo="Página que muestra el esquema de funcionamiento de un informe" icono="mdi-list-box-outline">
        <v-stepper editable v-model="step" :items="items" show-actions prev-text="Anterior" next-text="Continuar" alt-labels color="#333">
            <template v-slot:item.1>
                <h3 class="text-h6">Parámetros para mostrar el informe</h3>

                <br />

                <v-radio-group v-model="shipping" label="Método de entrega">
                    <v-radio label="Standard Shipping" value="5"></v-radio>
                    <v-radio label="Priority Shipping" value="10"></v-radio>
                    <v-radio label="Express Shipping" value="15"></v-radio>
                </v-radio-group>
            </template>

            <template v-slot:item.2>
                <h3 class="text-h6">Datos del informe</h3>

                <br />

                <v-sheet border>
                    <v-table>
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-end">Quantity</th>
                                <th class="text-end">Price</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(product, index) in products" :key="index">
                                <td v-text="product.name"></td>
                                <td class="text-end" v-text="product.quantity"></td>
                                <td class="text-end" v-text="product.quantity * product.price"></td>
                            </tr>

                            <tr>
                                <td>Shipping</td>
                                <td></td>
                                <td class="text-end" v-text="shipping"></td>
                            </tr>

                            <tr>
                                <th>Total</th>
                                <th></th>
                                <th class="text-end">${{ total }}</th>
                            </tr>
                        </tbody>
                    </v-table>
                </v-sheet>
            </template>
            <template v-slot:next></template>
            <template v-slot:prev>
                <v-row justify="start">
                    <v-col cols="auto">
                        <v-btn @click="step--" :disabled="step == 1" rounded variant="tonal" color="blue-darken-4" prepend-icon="mdi-arrow-left-bold">
                            <template v-slot:prepend>
                                <v-icon color="success"></v-icon>
                            </template>
                            Atrás
                        </v-btn>
                    </v-col>

                    <v-col cols="auto">
                        <v-btn @click="step++" :disabled="step != 1" rounded variant="tonal" color="blue-darken-4" append-icon="mdi-arrow-right-bold">
                            <template v-slot:append>
                                <v-icon color="success"></v-icon>
                            </template>
                            Siguiente
                        </v-btn>
                    </v-col>
                </v-row>
            </template>
        </v-stepper>
    </AppLayout>
</template>
