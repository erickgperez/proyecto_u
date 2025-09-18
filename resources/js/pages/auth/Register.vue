<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

const form = useForm({
    nie: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthBase :title="$t('auth._crear_una_cuenta_aspirante_')" :description="$t('auth._ingrese_datos_para_crear_cuenta_aspirante_')">
        <Head :title="$t('auth._registrar_')" />

        <form @submit.prevent="submit" class="flex flex-col gap-6">
            <div class="grid gap-6">
                <div class="grid gap-2">
                    <Label for="nie">{{ $t('NIE') }}</Label>
                    <Input
                        id="nie"
                        type="text"
                        required
                        autofocus
                        :tabindex="1"
                        autocomplete="nie"
                        v-model="form.nie"
                        :placeholder="$t('_numero_identificacion_estudiante_')"
                    />
                    <InputError :message="form.errors.nie" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">{{ $t('auth._direccion_email_') }}</Label>
                    <Input id="email" type="email" required :tabindex="2" autocomplete="email" v-model="form.email" placeholder="email@example.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">{{ $t('auth._clave_') }}</Label>
                    <Input
                        id="password"
                        type="password"
                        required
                        :tabindex="3"
                        autocomplete="new-password"
                        v-model="form.password"
                        :placeholder="$t('auth._clave_')"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation">{{ $t('auth._confirmar_clave_') }}</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        required
                        :tabindex="4"
                        autocomplete="new-password"
                        v-model="form.password_confirmation"
                        :placeholder="$t('auth._confirmar_clave_')"
                    />
                    <InputError :message="form.errors.password_confirmation" />
                </div>

                <Button type="submit" class="mt-2 w-full" tabindex="5" :disabled="form.processing">
                    <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                    {{ $t('auth._crear_cuenta_') }}
                </Button>
            </div>

            <div class="text-center text-sm text-muted-foreground">
                {{ $t('auth._tiene_una_cuenta_?_') }}
                <TextLink :href="route('login')" class="underline underline-offset-4" :tabindex="6">{{ $t('_ingresar_') }}</TextLink>
            </div>
        </form>
    </AuthBase>
</template>
