<script setup lang="ts">
import backgroundImage from '@/assets/images/pawel-czerwinski-unsplash.jpg';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <v-responsive class="rounded border">
        <v-app class="bg-grey-lighten-3">
            <v-navigation-drawer elevation="16" :location="$vuetify.display.mobile ? 'bottom' : 'right'" permanent :image="backgroundImage">
                <v-container class="d-flex align-center fill-height">
                    <v-row justify="center">
                        <v-col cols="auto">
                            <!-- Your div content here -->
                            <div class="text-h4 text-blue-darken-4 text-center">TEC <BR />EL SALVADOR</div>
                        </v-col>
                    </v-row>
                </v-container>
            </v-navigation-drawer>

            <v-main class="d-flex align-center fill-height">
                <v-card
                    border="opacity-30 lg primary"
                    rounded="xl"
                    elevation="16"
                    class="bg-blue-grey-darken-1 mx-auto text-white"
                    prepend-icon="mdi-school"
                    max-width="600"
                >
                    <template v-slot:title>
                        <span class="font-weight-black">Sistema Informático TEC El Salvador</span>
                    </template>

                    <v-card-text class="bg-white">
                        <div class="flex flex-col items-center">
                            <Link :href="route('home')" class="flex flex-col items-center font-medium">
                                <div class="flex h-9 w-9 items-center justify-center rounded-md">
                                    <AppLogoIcon class="size-9 fill-current text-[var(--foreground)] dark:text-white" />
                                </div>
                                <span class="sr-only">{{ title }}</span>
                            </Link>
                            <div class="space-y-2 text-center">
                                <h1 class="text-xl font-medium">{{ title }}</h1>
                                <p class="text-center text-sm text-muted-foreground">{{ description }}</p>
                            </div>
                        </div>
                        <slot />
                    </v-card-text>
                </v-card>
            </v-main>
        </v-app>
    </v-responsive>
</template>
